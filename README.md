# Loan Management System

## Introduction
Simple loan management system demonstrading user login (lender) , management of loans by lender.

This project is built using the Laravel framework, a robust and easy-to-use PHP framework for web applications.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Requirements

- **PHP**: 8.3
- **Composer**: 2.8.2
- **Node.js** and **npm**


## Installation

1. **Clone the repository:**

    ```bash
    git clone  https://github.com/surapuramakhil/loan-management.git
    cd loan-management
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies:**

    ```bash
    npm install
    ```

4. **Copy the example environment file and configure the environment variables:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Run database migrations:**

    ```bash
    php artisan migrate
    ```
it will prompt for creation of sqlite file - answer yes to it.

6. **Build the frontend assets:**

    ```bash
    npm run dev
    ```

## Usage

1. **Start the local development server:**

    ```bash
    php artisan serve
    ```

2. **Visit [http://localhost:8000](http://localhost:8000) in your browser to see the application in action.**

3. Visit [http://127.0.0.1:8000/docs/api#/](http://127.0.0.1:8000/docs/api#/) for API documentation

## Running Tests

To ensure that the application is working correctly, you can run the provided tests. Follow the steps below to run the tests:

**Run PHP tests:**

```bash
php artisan test
```