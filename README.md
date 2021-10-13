# ShURL
ShURL is a simple short URL website. It allows visitors to create shorter and/or easier URLs that links to other websites. 

## Softwares and dependencies
This website runs inside docker containers. 
The images used are [php](https://hub.docker.com/_/php), [nginx](https://hub.docker.com/_/nginx), and [mariadb](https://hub.docker.com/_/mariadb). The website uses the [Symfony 5](https://symfony.com/) framework.

Docker versions that have been used to run these containers are Docker Enginge v20.10.8 and Docker Compose v1.27.4. Earlier docker versions might work, but no guarantees are given.

## Setup
1. In `docker-compose.yml` look for the environment variables for _mariadb_.
```yml
  db:
    image: mariadb:latest
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: changeme
      MYSQL_DATABASE: shurl
      MYSQL_USER: shurl
      MYSQL_PASSWORD: changeme
    volumes:
      - ./db/:/var/lib/mysql/
```
Change these variables to what works for you.

2. Next look for the _nginx_ section.
```yml
  nginx:
    image: nginx:alpine
    ports:
      - '8080:80'
    volumes:
      - ./php/app/:/var/www/symfony_docker/
      - ./nginx/:/etc/nginx/conf.d/
    depends_on:
      - php
      - db
```
Change the host system port (`8080` in the example above) to a free port on your system.

3. Next open the `.env` file in the `php/app` directory, and look for the `DATABASE_URL` variable.
```bash
DATABASE_URL="mysql://shurl:changeme@db:3306/shurl"
```
Change the URL to match the environment variables in the `docker-compose.yml` file.

4. Run the docker-compose file.
```bash
$ docker-compose up -d
```

5. When all the containers are up and running, and the database has been initialized, run the `init.sh` script within the php container.
```bash
$ docker exec -ti shurl_php_1 sh init.sh
```
This will create the database and table for ShURL, and setup the dependencies for Symfony.

## Deploy
To deploy a production environment of ShURL, copy the `.env` file in the `php/app` directory to `.env.local` and open the copy. Look for `APP_ENV`
```bash
APP_ENV=dev
```
and change it to
```bash
APP_ENV=prod
```
After that run the `deploy.sh` script inside the php container.
```bash
$ docker exec -ti shurl_php_1 sh deploy.sh
```
The website should now run in production mode.
