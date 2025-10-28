FROM golang:1.24-alpine AS builder

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download
COPY . .

RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o kuturl-api .

ENTRYPOINT ["./kuturl-api"]

# # BUILD
# FROM gcr.io/distroless/static-debian12:nonroot
# 
# WORKDIR /app
# 
# EXPOSE 5502
# 
# ENTRYPOINT ["./kuturl-api"]