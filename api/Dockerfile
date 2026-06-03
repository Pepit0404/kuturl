FROM golang:1.24-alpine AS builder

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download
COPY . .

RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o kuturl-api .

FROM alpine:3.11 AS deploy

WORKDIR /app

ENV ENVIRONMENT=docker

COPY ./sql ./sql
COPY --from=builder /app/kuturl-api .

EXPOSE 4481
ENTRYPOINT ["./kuturl-api"]
