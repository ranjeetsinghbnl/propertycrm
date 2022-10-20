## Property CRM

A demo application to illustrate how [Inertia.js](https://inertiajs.com/) + [VueJs](https://vuejs.org/) + [Laravel](https://laravel.com) works together.
![](https://raw.githubusercontent.com/ranjeetsinghbnl/propertycrm/main/Screenshot.png)


## Prerequisite
I'm using [Laravel sail](https://laravel.com/docs/9.x/sail) for setup.
- Docker

## Installation


Clone the repo locally:

```sh
git clone https://github.com/ranjeetsinghbnl/propertycrm propertycrm
cd propertycrm
```

**Please make sure docker is running**


Install PHP dependencies:

```sh
./vendor/bin/sail composer install
```

Install NPM dependencies:

```sh
./vendor/bin/sail npm install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
./vendor/bin/sail php artisan key:generate
```

Run database migrations:

```sh
./vendor/bin/sail php artisan migrate
```

Run database seeder:

```sh
./vendor/bin/sail php artisan db:seed
```

Link storage:

```sh
./vendor/bin/sail php artisan storage:link
```

Build assets / Start front end server:

```sh
./vendor/bin/sail npm run dev
```

You're ready to go! Visit Property CRM in your browser, and login with:

- **Username:** johndoe@example.com
- **Password:** password

## Running tests

To run the Property CRM tests, run:

```sh
./vendor/bin/sail php artisan test
```

## [Tech Stack and Approach](./tech-spec.md)


## Todo and Improvements
- Test cases for Vue components
- Repository pattern
- UI
- Translation and configuration files for flash messages
- User Profile and Auth
- Improve service for fetching properties data from third party vendors
- Husky
- CI/CD setup
