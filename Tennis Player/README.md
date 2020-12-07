# Tennis Player
============================

### Installation

- Make sure that you are using php 7.3+
- Fork into your repository and Clone this repository. ex: `https://github.com/ranakrisna/backend-assessment-evermos.git`
The directory name should be `backend-assessment-evermos` 
- Go to the folder named `Tennis Player`
    >$ `cd Tennis Player`
- copy `.env` from `.env.example` 
    >$ `cp .env.example .env`
- install the vendor using composer
    >$ `composer install`
- create new database named `tennis_player`
- migrate the tables
    >$ `php artisan migrate`

### Running Application
- Run application
    >$ `php artisan serve`
- This application will run on `localhost:8000`

### Documentation
[https://app.swaggerhub.com/apis/ranakrisna/1tennis_player/1.0.0](https://app.swaggerhub.com/apis/ranakrisna/1tennis_player/1.0.0)

### Running Test
- Run testing
    >$ `php artisan test`