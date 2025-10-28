package models

type URL struct {
	LongURL  string `json:"long_url"`
	ShortURL string `json:"short_url"`
	CountUse int    `json:"count_use"`
}
