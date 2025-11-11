package controllers

import (
	"database/sql"
	"kuturl/app"
	"kuturl/models"
	"log"
	"math/rand"
	"net/http"

	"github.com/gin-gonic/gin"
)

const characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"
const SHORTER_URL_LENTH = 5

func generateShorterUrl(n int) string {
	shorter := make([]byte, n)
	for i := range shorter {
		shorter[i] = characters[rand.Intn(len(characters))]
	}

	return string(shorter)
}

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
		shorterURL = generateShorterUrl(SHORTER_URL_LENTH)
	} else {
		shorterURL = tmpShorterURL
	}

	url := &models.URL{
		LongURL:  originalURL,
		ShortURL: shorterURL,
		CountUse: 0,
	}

	for {
		_, err := ctrl.app.Services.URLService.GetOriginalURL(shorterURL)
		if err != nil {
			if err != sql.ErrNoRows && tmpShorterURL != "" {
				return http.StatusConflict, "Short URL already exists"
			} else if err != sql.ErrNoRows {
				shorterURL = generateShorterUrl(SHORTER_URL_LENTH)
			} else {
				break
			}
		}
	}

	// Short URL does not exist, proceed to create
	createdURL, err := ctrl.app.Services.URLService.CreateShortURL(url)
	if err != nil {
		return http.StatusInternalServerError, err
	}

	return http.StatusOK, createdURL
}
