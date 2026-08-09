package utils

import (
	"log"

	"github.com/gin-gonic/gin"
)

func Success(data any) gin.H {
	return gin.H{
		"status": "success",
		"result": data,
	}
}

func Error(message any) gin.H {
	return gin.H{
		"status":  "error",
		"message": message,
	}
}

func Response(f func(c *gin.Context) (int, any)) gin.HandlerFunc {
	return func(c *gin.Context) {
		code, body := f(c)
		switch code {
		case 200:
			c.JSON(code, Success(body))
		case 201:
			c.JSON(code, Success(body))
		default:
			log.Default().Println("Error:", body)
			c.JSON(code, Error(body))
		}
	}
}
