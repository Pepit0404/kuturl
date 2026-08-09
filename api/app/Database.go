package app

import (
	"database/sql"
	"log"
	"os"
	"time"
)

func DBConnection(config Config) *sql.DB {
	User := os.Getenv("DB_USER")
	Password := os.Getenv("DB_PASSWORD")

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
	log.Default().Println("Connecting to database: mysql", "on", Host+":"+Port, "with user", User)

	db, err := sql.Open("mysql", User+":"+Password+"@tcp("+Host+":"+Port+")/"+Name)
	if err != nil {
		log.Fatal("invalid database config: %w", err)
		panic(1)
	}

	if err := db.Ping(); err != nil {
		log.Fatal("database unreachable: %w", err)
	}

	db.SetConnMaxLifetime(time.Minute * 3)
	return db
}
