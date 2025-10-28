package app

import (
	"database/sql"
	"log"
	"os"
	"time"
)

func DBConnection(config Config) (connecton *sql.DB) {
	User := os.Getenv("DB_USER")
	Password := os.Getenv("DB_PASSWORD")

	Type := os.Getenv("DB_TYPE")
	if Type == "" {
		Type = config.DB.Type
	}

	Host := os.Getenv("DB_HOST")
	if Host == "" {
		Host = config.DB.Host
	}

	Port := os.Getenv("DB_PORT")
	if Port == "" {
		Port = config.DB.Port
	}

	Name := os.Getenv("DB_NAME")
	if Name == "" {
		Name = config.DB.Name
	}
	log.Default().Println("Connecting to database:", Type, "on", Host+":"+Port, "with user", User)

	connecton, err := sql.Open(Type, User+":"+Password+"@tcp("+Host+":"+Port+")/"+Name)
	if err != nil {
		panic(err.Error())
	}

	connecton.SetConnMaxLifetime(time.Minute * 3)
	return connecton
}
