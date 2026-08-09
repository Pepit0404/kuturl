# kuturl

Minimalist url shortener

## Deploy

### Create a docker compose file**

```bash
mkdir kuturl
cd kuturl
vi docker-compose.yml
```

Past this:

```yml
services:
    db:
        image: mysql:8.0
        container_name: kuturl_db
        environment:
            MYSQL_USER: ${DB_USER:-kuturl}
            MYSQL_PASSWORD: ${DB_PASSWORD:-password}
            MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-rootpassword}
            MYSQL_DATABASE: ${DB_NAME:-kuturl}
        restart: unless-stopped
        volumes:
            - kuturl-data:/var/lib/mysql
        ports:
            - "${DB_EXTERNAL_PORT:-3366}:3306"
        networks:
            - kuturl_network
        healthcheck:
            test:
                [
                    "CMD-SHELL",
                    "mysql -u$${MYSQL_USER} -p$${MYSQL_PASSWORD} -e 'SELECT 1' $${MYSQL_DATABASE}",
                ]
            timeout: 10s
            retries: 5
            interval: 30s
            start_period: 30s

    api:
        container_name: kuturl_api
        image: ghcr.io/pepit0404/kuturl-api:latest
        environment:
            PORT: ${API_PORT:-4481}
            DB_USER: ${DB_USER:-kuturl}
            DB_PASSWORD: ${DB_PASSWORD:-password}
            DB_NAME: ${DB_NAME:-kuturl}
            DB_HOST: ${DB_HOST:-kuturl_db}
            DB_PORT: ${DB_PORT:-3306}
        ports:
            - "${API_PORT:-4481}:4481"
        restart: unless-stopped
        networks:
            - kuturl_network
        depends_on: [db]

    front:
        container_name: kuturl_front
        image: ghcr.io/pepit0404/kuturl-frontend:latest
        environment:
            BASEPATH: "${FRONTEND_BASEPATH:-}"
        ports:
            - "${FRONTEND_PORT:-4480}:80"
        restart: unless-stopped
        networks:
            - kuturl_network

networks:
    kuturl_network:
        driver: bridge

volumes:
    kuturl-data:
```

### Customize environment variables

```bash
# Download .env.example
curl https://raw.githubusercontent.com/Pepit0404/kuturl/main/.env.example -O .env.example

# Configure environment
cp .env.example .env
```

### Start the application

```bash
docker compose up -d
```

## Access points

- 🌐 **Frontend**: <http://localhost:4480>
- 🔌 **Backend API**: <http://localhost:4481>
- 🗄️ **DataBase**: `localhost:3366` _user:kuturl , password:shorterurl_
