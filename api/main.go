package main

import (
	"kuturl/setup"
	"log"
)

func main() {
	server, application := setup.SetupServer()
	log.Default().Println("Server is running on port " + application.Config.Port)
	defer application.DB.Close()

	server.Run(":" + application.Config.Port)
}
