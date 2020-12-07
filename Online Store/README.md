# Online Store
============================

### Installation

- Make sure that you are using php 7.3+
- Fork into your repository and Clone this repository. ex: `https://github.com/ranakrisna/backend-assessment-evermos.git`
The directory name should be `backend-assessment-evermos` 
- Go to the folder named `Online Store`
    >$ `cd 'Online Store'`
- copy `.env` from `.env.example` 
    >$ `cp .env.example .env`
- configure database setting from `.env`
- install the vendor using composer
    >$ `composer install`
- create new database named same as `.env`
- migrate the tables
    >$ `php artisan migrate`

### Running Application
- Run application
    >$ `php artisan serve`
- This application will run on `localhost:8000`

### Documentation
[https://documenter.getpostman.com/view/12838286/TVmS6aD4](https://documenter.getpostman.com/view/12838286/TVmS6aD4)

### Running Test
- copy `.env.testing` from `.env.example` 
    >$ `cp .env.example .env.testing`
- configure database setting from `.env.testing`
- create new database named same as `.env.testing`
- migrate the tables
    >$ `php artisan migrate --env=testing`
- Run testing
    >$ `php artisan test --env=testing`