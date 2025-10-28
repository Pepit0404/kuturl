package app

import (
	"database/sql"
	"kuturl/services"

	_ "github.com/go-sql-driver/mysql"
)

type DataBase struct {
	Type string
	Host string
	Port string
	Name string
}

type Config struct {
	Port string
	DB   DataBase
}

type Services struct {
	URLService services.URLService
}

type App struct {
	DB       *sql.DB
	Services Services
	Config   Config
}

func InitializeApplication(config Config) *App {
	var app App
	app.Config = config
	app.DB = DBConnection(config)
	app.Services = Services{
		URLService: services.NewUrlService(app.DB),
	}

	return &app
}
