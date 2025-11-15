package models

type URL struct {
	LongURL  string `json:"original_url"`
	ShortURL string `json:"shorter_url"`
	CountUse int    `json:"count_use"`
}
