<div align="center">

# Softgorillas recruitment task

![Tests](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)
![Code coverage](https://raw.githubusercontent.com/Juraso2/softgorillas-recruitment/image-data/coverage.svg)

This application is a recruitment task for Softgorillas company.

Application is responsible for processing message collection and saving it to json file.<br />
Application is dockerized and uses docker-compose to run.

[Getting Started](#getting-started) •
[Running command](#running-command) •
[Running tests](#running-tests)

![Example](/img/example.png)

</div>

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull -d --wait` to start the project
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Running command

To run a command, first you need to enter the php container by running 
```sh
docker compose exec php sh
```
Then you can run 
```sh
php bin/console app:process-collection
``` 
to process message collection.

## Running tests

To run tests, first you need to enter the php container by running 
```sh
docker compose exec php sh
```
Then you can run 
```sh
php bin/phpunit
```
