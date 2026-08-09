package services

import (
	"database/sql"
	"kuturl/models"
	"testing"

	"github.com/DATA-DOG/go-sqlmock"
)

func TestGetOriginalURL_Success(t *testing.T) {
	db, mock, err := sqlmock.New()
	if err != nil {
		t.Fatalf("Failed to create mock db: %v", err)
	}
	defer db.Close()

	shortURL := "abc123"
	expectedLongURL := "https://example.com/very/long/url"
	expectedCountUse := 5

	rows := sqlmock.NewRows([]string{"longUrl", "shortUrl", "countUse"}).
		AddRow(expectedLongURL, shortURL, expectedCountUse)

	mock.ExpectQuery("SELECT longUrl, shortUrl, countUse FROM Links WHERE shortUrl = ?").
		WithArgs(shortURL).
		WillReturnRows(rows)

	service := NewUrlService(db)
	result, err := service.GetOriginalURL(shortURL)

	if err != nil {
		t.Errorf("GetOriginalURL returned an error: %v", err)
	}

	if result == nil {
		t.Errorf("GetOriginalURL returned nil")
	}

	if result.LongURL != expectedLongURL {
		t.Errorf("Expected LongURL %s, got %s", expectedLongURL, result.LongURL)
	}

	if result.ShortURL != shortURL {
		t.Errorf("Expected ShortURL %s, got %s", shortURL, result.ShortURL)
	}

	if result.CountUse != expectedCountUse {
		t.Errorf("Expected CountUse %d, got %d", expectedCountUse, result.CountUse)
	}

	if err := mock.ExpectationsWereMet(); err != nil {
		t.Errorf("Mock expectations not met: %v", err)
	}
}

func TestGetOriginalURL_NotFound(t *testing.T) {
	db, mock, err := sqlmock.New()
	if err != nil {
		t.Fatalf("Failed to create mock db: %v", err)
	}
	defer db.Close()

	shortURL := "nonexistent"

	rows := sqlmock.NewRows([]string{"longUrl", "shortUrl", "countUse"})

	mock.ExpectQuery("SELECT longUrl, shortUrl, countUse FROM Links WHERE shortUrl = ?").
		WithArgs(shortURL).
		WillReturnRows(rows)

	service := NewUrlService(db)
	result, err := service.GetOriginalURL(shortURL)

	if err != sql.ErrNoRows {
		t.Errorf("Expected sql.ErrNoRows, got %v", err)
	}

	if result != nil {
		t.Errorf("Expected nil result, got %v", result)
	}

	if err := mock.ExpectationsWereMet(); err != nil {
		t.Errorf("Mock expectations not met: %v", err)
	}
}

func TestGetOriginalURL_QueryError(t *testing.T) {
	db, mock, err := sqlmock.New()
	if err != nil {
		t.Fatalf("Failed to create mock db: %v", err)
	}
	defer db.Close()

	shortURL := "abc123"

	mock.ExpectQuery("SELECT longUrl, shortUrl, countUse FROM Links WHERE shortUrl = ?").
		WithArgs(shortURL).
		WillReturnError(sql.ErrConnDone)

	service := NewUrlService(db)
	result, err := service.GetOriginalURL(shortURL)

	if err == nil {
		t.Errorf("Expected an error, got nil")
	}

	if result != nil {
		t.Errorf("Expected nil result, got %v", result)
	}

	if err := mock.ExpectationsWereMet(); err != nil {
		t.Errorf("Mock expectations not met: %v", err)
	}
}

func TestCreateShortURL_Success(t *testing.T) {
	db, mock, err := sqlmock.New()
	if err != nil {
		t.Fatalf("Failed to create mock db: %v", err)
	}
	defer db.Close()

	urlData := &models.URL{
		LongURL:  "https://example.com/very/long/url",
		ShortURL: "abc123",
		CountUse: 0,
	}

	mock.ExpectExec("INSERT INTO Links \\(longUrl, shortUrl, countUse\\) VALUES \\(\\?, \\?, \\?\\)").
		WithArgs(urlData.LongURL, urlData.ShortURL, 0).
		WillReturnResult(sqlmock.NewResult(1, 1))

	service := NewUrlService(db)
	result, err := service.CreateShortURL(urlData)

	if err != nil {
		t.Errorf("CreateShortURL returned an error: %v", err)
	}

	if result == nil {
		t.Errorf("CreateShortURL returned nil")
	}

	if result.LongURL != urlData.LongURL {
		t.Errorf("Expected LongURL %s, got %s", urlData.LongURL, result.LongURL)
	}

	if result.ShortURL != urlData.ShortURL {
		t.Errorf("Expected ShortURL %s, got %s", urlData.ShortURL, result.ShortURL)
	}

	if err := mock.ExpectationsWereMet(); err != nil {
		t.Errorf("Mock expectations not met: %v", err)
	}
}

func TestCreateShortURL_DuplicateError(t *testing.T) {
	db, mock, err := sqlmock.New()
	if err != nil {
		t.Fatalf("Failed to create mock db: %v", err)
	}
	defer db.Close()

	urlData := &models.URL{
		LongURL:  "https://example.com/very/long/url",
		ShortURL: "abc123",
		CountUse: 0,
	}

	mock.ExpectExec("INSERT INTO Links \\(longUrl, shortUrl, countUse\\) VALUES \\(\\?, \\?, \\?\\)").
		WithArgs(urlData.LongURL, urlData.ShortURL, 0).
		WillReturnError(sql.ErrNoRows)

	service := NewUrlService(db)
	result, err := service.CreateShortURL(urlData)

	if err == nil {
		t.Errorf("Expected an error, got nil")
	}

	if result == nil {
		t.Errorf("Expected result to be returned even on error, got nil")
	}

	if err := mock.ExpectationsWereMet(); err != nil {
		t.Errorf("Mock expectations not met: %v", err)
	}
}
