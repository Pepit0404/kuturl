package routes

import (
	"kuturl/app"
	"kuturl/controllers"
	"kuturl/utils"

	"github.com/gin-gonic/gin"
)

func Url(app *app.App, router *gin.RouterGroup) {
	url := router.Group("/url")
	urlController := controllers.NewURLController(app)

	url.GET("/:short", utils.Response(urlController.GetOriginalURL))
	url.POST("", utils.Response(urlController.CreateShortURL))
}
