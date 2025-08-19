# commercetools-php-sdk-developer-training

A training project demonstrating the commercetools PHP SDK in a fullstack setup using **Laravel** for the Backend-for-Frontend (BFF) and a lightweight frontend built with **HTML, CSS, and JavaScript**.

**Important Note:**  
We are using **Laravel** for demonstration purposes in this project. It is not a recommendation or endorsement of Laravel over other server-side frameworks. Any other php-based server-side framework (such as Symfony, Laminas, CodeIgniter etc.) can be used to build a similar Backend-for-Frontend (BFF) layer. The choice of framework is entirely up to your preferences and project requirements.

## 🎯 Goals

- Learn how to use the commercetools PHP SDK in a backend service.
- Explore a simple UI that interacts with the BFF to trigger commercetools API calls.
- Practice realistic commerce use cases like product listing, cart management, and checkout flow.

## 🗂️ Project Structure

- **Backend**: Laravel-based BFF layer.
- **Frontend**: Lightweight UI using HTML, CSS, and JavaScript for interacting with the backend.
- **commercetools SDK**: Demonstrates integrations with commercetools’ APIs.

## Features

- Provides a basic Laravel setup for interacting with commercetools APIs.
- Includes a simple frontend for triggering commerce actions (e.g., product browsing, cart management, and checkout).
- Full-stack application structure to demonstrate how to use the commercetools PHP SDK effectively.

## Getting Started

### Prerequisites
1. PHP

Laravel 10+ requires PHP 8.1 or higher. Install PHP via:

**macOS:** brew install php

**Windows:** XAMPP or WSL

2. Composer

Laravel uses Composer to manage dependencies. Install it: https://getcomposer.org/download/

3. Laravel 

Install Laravel Installer globally:

```bash
$ composer global require laravel/installer
```

### Installation

Clone this project and run `composer install` to install the dependencies: 

```bash
$ composer install
```

### Setup API Client in Merchant Center

Before running the project, you need to create an API client in the commercetools Merchant Center and provide its credentials in the `.env` file.

1. Go to the [commercetools Merchant Center](https://mc.europe-west1.gcp.commercetools.com/).
2. Create a new API client:
   - Navigate to **Project settings** > **API clients**.
   - Click on **Create API Client** and select Admin client template from drop down list.
   - Make a note of the **Project Key**, **Client ID**, **Client Secret** and **scopes**.


### Running the Project

For development:

```bash
$ php artisan serve
```

### Frontend

For viewing the frontend, plese go to http://localhost:8000

## License

This project is licensed under the MIT License. See the [LICENSE](https://github.com/nestjs/nest/blob/master/LICENSE) file for details.


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
