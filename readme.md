# Scrapper 


## Installation
- Clone this project and install docker in your machine


## Running
 - Run the following command to execute the script
```dockerfile
- docker-compose run app sh -c "php Main.php"
```

## Info
Make sure you set the username and password in docker-compose.yml
```yaml
version: '3.1'

services:
  app:
    build:
      context: app
      dockerfile: Dockerfile
    volumes:
      - ./app:/app
    environment:
      USERNAME: 'username'
      PASSWORD: 'password'
      DEBUG: 0

```
