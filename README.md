# Backend Project (Laravel)

This is the backend part of the application. Built with Laravel and containerised using Docker.

# Requirements

Install the following dependencies before you start:

- Git
- Docker and Docker Compose

## How to setup the project locally

Copy the repository onto your disc:
`git clone https://github.com/sawczuk24427/laravel-api-docker.git`

Go into the project directory:
`cd laravel-api-docker`

Create environmental config file (from example):
`cp .env.example .env`

Open `.env` file and make sure the development ports don`t collide with your system

Build and run Docker containers:
`docker compose up -d`

Go into the app container:
`docker compose exec app bash`

Install PHP dependencies there:
`composer install`

Generate the app encryption key:
`php artisan key:generate`

Run migrations, to create tables in the database:
`php artisan migrate --seed`

## App access

- API address: http://localhost:8000
- Database port: 3307
