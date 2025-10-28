package setup

import (
	"kuturl/app"
	"kuturl/router"
	"log"
	"os"

	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
)

func SetupServer() (*gin.Engine, *app.App) {
	godotenv.Load()

	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
		os.Exit(1)
	}

	log.Println("Environment variables loaded successfully")

	config := app.Config{}
	config.Port = os.Getenv("PORT")

	// Initialize app's configuration
	application := app.InitializeApplication(config)

	// Initialize router
	router := router.InitRouter(application)

	return router, application
}
