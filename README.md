## Time Tracker app

This app has been designed to keep track of different tasks, such as cleaning, cooking, walking the dog or whatever task you want!

## Requirements

- Composer
- PHP 8.1
- php8.1-curl, php8.1-mbstring, php8.1-xml

## Installation

- Clone the repo to your machine
- Copy the `.env.example` file to a new file named `.env` and add the following details:
    - `DB_HOST=mysql`
    - `DB_USERNAME=sail`
    - `DB_PASSWORD=password`
- Run `composer update`
- Run `composer install`
- Using the terminal in the app's root folder, execute `./vendor/bin/sail up`. Then, leave this terminal running.
- Once the previous command has finished installing, in another terminal, execute `./vendor/bin/sail artisan key:generate`
- Execute `./vendor/bin/sail artisan migrate`
- Open the app using your browser in localhost

## Some problems you may encounter

If you start the project without migrating the DB, there will be errors related to that. To fix them:
- `./vendor/bin/sail down --rmi all -v`
- `./vendor/bin/sail up`

If there's an error involving ports used by other applications, you may change them from the .env file.

## Usage

Simply write a new task and hit the "Start" button. You'll see the timer starting to count. When you finish doing your task, hit the "Stop" button and this task will be saved into the database. To see your task history, refresh the page.
