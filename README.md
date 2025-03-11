# Task Management System

This is a Laravel-based task management system API.

## Getting Started

These instructions will guide you through setting up and running the application.

### Prerequisites

* PHP >= 8.3
* Composer
* MySQL or another supported database

### Installation

1.  **Clone the repository:**

    ```bash
    git clone [https://github.com/joslinselva/task-management-system.git](https://www.google.com/search?q=https://github.com/joslinselva/task-management-system.git)
    cd task-management-system
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Copy the `.env.example` file to `.env` and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

    * Update the `.env` file with your database credentials and mail settings.

4.  **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

### Running the Application

1.  **Start the development server:**

    ```bash
    php artisan serve
    ```

    * The API will be available at `http://127.0.0.1:8000`.

### Running Queue Workers

* To process queued jobs (e.g., sending emails), start a queue worker:

    ```bash
    php artisan queue:work --tries=3 --sleep=10
    ```

    * `--tries=3` specifies that a job should be attempted up to three times before being marked as failed.
    * `--sleep=10` specifies that the worker should sleep for 10 seconds between attempts if a job fails.

### Running Scheduled Tasks

* To execute scheduled tasks defined in `bootstrap/app.php`, run the Laravel scheduler:

    ```bash
    php artisan schedule:run
    ```

    * **Important:** In a production environment, you should configure a cron job to run this command every minute.

### Running the Expire Overdue Tasks Command

* To manually run the `tasks:expire-overdue` Artisan command, which marks overdue tasks as expired, execute:

    ```bash
    php artisan tasks:expire-overdue
    ```

    * This command is also scheduled to run hourly via the Laravel scheduler.

### API Information

* This is a RESTful API. Use tools like Postman to interact with the endpoints.
* Authentication is handled via Laravel Sanctum.
* Please see the route files for available endpoints.

### Additional Notes

* Ensure your database server is running before attempting to run migrations or access the application.
* For email functionality, configure your `.env` file with the appropriate mail settings.