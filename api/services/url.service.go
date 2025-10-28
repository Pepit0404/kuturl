package services

import (
	"database/sql"
	"kuturl/models"
	"log"
)

type URLService interface {
	GetOriginalURL(shortURL string) (*models.URL, error)
	CreateShortURL(url *models.URL) (*models.URL, error)
}

func NewUrlService(db *sql.DB) URLService {
	return &Service{db: db}
}

func (s *Service) GetOriginalURL(shortURL string) (*models.URL, error) {
	longUrl := &models.URL{}

	log.Default().Println("Start GetOriginalURL for:", shortURL)
	rows, err := s.db.Query("SELECT longUrl, shortUrl, countUse FROM Links WHERE shortUrl = ?", shortURL)
	if err != nil {
		log.Default().Println(err)
		return nil, err
	}

	defer rows.Close()

	if rows.Next() {
		if err := rows.Scan(&longUrl.LongURL, &longUrl.ShortURL, &longUrl.CountUse); err != nil {
			return nil, err
		}
	} else {
		return nil, sql.ErrNoRows
	}

	return longUrl, nil
}

func (s *Service) CreateShortURL(url *models.URL) (*models.URL, error) {
	_, err := s.db.Exec("INSERT INTO Links (longUrl, shortUrl, countUse) VALUES (?, ?, ?)", url.LongURL, url.ShortURL, 0)
	if err != nil {
		return url, err
	}

	return url, nil
}
