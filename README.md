# Laravel RESTful API

<p align="center">
<a href="https://github.com/turb0bur/laravel-rest/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
</p>

RESTful API based on PHP Laravel framework

## Installation

#### Install Composer dependencies
```bash
composer install
```
#### Configure environment variables `.env`

At least database connection should be configured
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel-rest
DB_USERNAME=db-username
DB_PASSWORD=db-password
```

#### Run Migrations and Seed the database with the dummy data
```bash
php artisan migrate:fresh --seed
```
#### Install npm dependencies
```bash
npm install
```
#### Build frontend assets
```bash
npm run build
```

## Usage

#### Create OAuth2 client using [Laravel Passport](https://laravel.com/docs/10.x/passport)
`grant_type=client_credentials`
```bash
php artisan passport:client

 Which user ID should the client be assigned to? (Optional):
 > 0

 What should we name the client?:
 > API Client 1

 Where should we redirect the request after authorization? [http://laravel-rest.local/auth/callback]:
 >

New client created successfully.
Client ID: 1
Client secret: 7XErrPMqfwfcP1tttW6x8Es2WhACCJaFm5O3SuT2
```
<hr>

`grant_type=password`
```bash
php artisan passport:client --password

 What should we name the password grant client? [LaravelRest Password Grant Client]:
 > API Client 2

 Which user provider should this client use to retrieve users? [users]:
  [0] users
 >

Password grant client created successfully.
Client ID: 2
Client secret: h1h5yeI0qYee6bzHGAiivXK2EBCRLb7FuGuzl3cd
```