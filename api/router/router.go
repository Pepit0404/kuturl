package router

import (
	"kuturl/app"
	"kuturl/router/routes"

	"github.com/gin-gonic/gin"
)

func InitRouter(app *app.App) *gin.Engine {
	router := gin.New()

	router.Use(gin.Recovery())

	router.SetTrustedProxies([]string{"127.0.0.1", "localhost", "kuturl_api", "kuturl_front"})

	api := router.Group("/api")

	api.GET("/ping", func(c *gin.Context) {
		c.JSON(200, gin.H{"message": "pong"})
	})

	routes.Url(app, api)

	return router
}
