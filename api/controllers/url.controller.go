package controllers

import (
	"database/sql"
	"kuturl/app"
	"kuturl/models"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
)

type URLController interface {
	GetOriginalURL(c *gin.Context) (int, any)
	CreateShortURL(c *gin.Context) (int, any)
}

func NewURLController(app *app.App) URLController {
	return &Controller{app: app}
}

func (ctrl *Controller) GetOriginalURL(c *gin.Context) (int, any) {
	shortURL := c.Param("short")
	if shortURL == "" {
		return http.StatusBadRequest, "Short URL parameter is missing"
	}

	originalURL, err := ctrl.app.Services.URLService.GetOriginalURL(shortURL)
	if err != nil {
		return http.StatusNotFound, err.Error()
	}

	if originalURL == nil {
		return http.StatusBadRequest, "There is no link using : " + shortURL
	}

	log.Default().Println("Original URL found:", originalURL.LongURL)
	return http.StatusOK, originalURL
}

func (ctrl *Controller) CreateShortURL(c *gin.Context) (int, any) {
	originalURL := c.PostForm("original_url")
	if originalURL == "" {
		return http.StatusBadRequest, "Original URL parameter is missing"
	}

	var shorterURL string
	tmpShorterURL := c.PostForm("shorter_url")
	if tmpShorterURL == "" {
		return http.StatusBadRequest, "Short URL parameter is missing"
		// shorterURL = "" TODO: generate a new short URL here
	} else {
		shorterURL = tmpShorterURL
	}

	url := &models.URL{
		LongURL:  originalURL,
		ShortURL: shorterURL,
		CountUse: 0,
	}

	_, err := ctrl.app.Services.URLService.GetOriginalURL(shorterURL)
	if err != nil {
		if err != sql.ErrNoRows {
			return http.StatusConflict, "Short URL already exists"
		}
		// Short URL does not exist, proceed to create
	}

	createdURL, err := ctrl.app.Services.URLService.CreateShortURL(url)
	if err != nil {
		return http.StatusInternalServerError, err
	}

	return http.StatusOK, createdURL
}
