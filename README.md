# About the code

This code uses Repository & Service pattern in this boiler plate to maintain clean code.

All business logic code should go inside service. **(App\Service)**
All Data access & manupulating logics should go inside repository. **(App\Repository)**
(Using eloquent/query builder directly from the service/controller should be avoided)

There are commands to generate Repository & Services. Feel free to read those code **(App\Console\Commands\Custom)**

### Creating Repository


`php artisan repository:create Users/User`

Every Repository must have a model. The above command will create a UserRepository by injecting Users\User model as its model (Note the repository sufix will automatically added) and interface for it.

The previous command will not create model, However you can pass -M flag to automatically create model for you along with the repository

`php artisan repository:create Users/User -Mmfsc`

The above command will create model, migration factory, seeder and controller. You can pass the flags according to your needs.

Here is the usage for the command
```
Usage:
  php artisan repository:create [options] [--] <name>

Arguments:
  name                  

Options:
  -M, --model           
  -m, --migration       
  -f, --factory         
  -s, --seeder          
  -c, --controller 
```

At last you have to bind the generated interface and class, so that laravel will know which concret class to inject for interfaces.

In App\Providers\EloquentRepositoryServiceProvider.php, add the following line.

```
<?php

use App\Repository\Interfaces\Users\UserRepositoryInterface;
use App\Repository\Eloquent\Users\UserRepository;
...
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
```
You should bind every repositories you create.
### Creating Services

Service will have business logics, so service might needs to access data. It should access data only from repository. (i.e service depends only on repository and not ORM)

`php artisan service:create Users/User`

The above command will will create service called UserService (Service sufix is automatically added)

You can pass dependencies as paramenters in the service create command for auto inject them.

`php artisan service:create Users/User Users/User`

The above command will create UserService and injectes UserRepository (interface) into the service

Here is the usage for the command
```
Usage:
  php artisan service:create <name> [<dependsOn>...]

Arguments:
  name                  
  dependsOn             

```

### The boiler plate

It is easy to understand the archetecture by showing the example code than theoritical explanation. Here you have basic api with 3 endpoints (User registration, User Login, See current user seession details)

```
+------+---------------------------+-----------------------+-------------------------------------------+----------------+------------+
| Verb | Path                      | NamedRoute            | Controller                                | Action         | Middleware |
+------+---------------------------+-----------------------+-------------------------------------------+----------------+------------+
| GET  | /                         |                       | None                                      | Closure        |            |
| POST | /api/user/register        | users.register        | App\Http\Controllers\Users\UserController | register       |            |
| POST | /api/user/login           | users.login           | App\Http\Controllers\Users\UserController | login          |            |
| GET  | /api/user/session-details | users.session-details | App\Http\Controllers\Users\UserController | sessionDetails | auth       |
+------+---------------------------+-----------------------+-------------------------------------------+----------------+------------+
```

Please go through the code for better understanding.


### Test case

The test case is configured to generate documentation by request & response. Just add the following line at end of the test case method to generate documentation.
`app('DocGen')->make(__FUNCTION__, $this->request, $response);`

If you want to use long description (not from function name), simply replace \_\_FUNCTION\_\_ with the custom name followed by the convention we mentioned above (Eg: testTitleLongDescriptionYouWant).

### Steps to ignite the app

Run the commands below
```
git clone https://github.com/cybersrikanth/laravel-lumen-boilerplate.git

cd laravel-lumen-boiler-plate

cp .env.example .env
cp .env.example .env.testing
```
 Edit the newly created .env and .env.testing files apropiratly.

Run `composer install` to install dependencies.
Run `php artisan migrate` to run migrations.
Run `vendor/bin/phpunit` to run test.

After running test, you may wish to see the documentation generated.

Run `ln -s $PWD/storage/app/public $PWD/public/storage` command to create symbolic link for app/public folder in public/storage (As our documentation is saved in app/public which is not document root we have to create sym link to expose it) This step only has to be done once

Once sym link created, run `php artisan serve`

Your documentation will be published in `/storage/docs.html` path.




# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

