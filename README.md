# Time Tracker app

This app was created in Laravel 9 and it has been designed to keep track of different tasks, such as cleaning, cooking, walking the dog or whatever task you want! You can use it with a browser or with the terminal.

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
    - Optional: to avoid writing `./vendor/bin/sail` everytime, you can create an alias to just write "sail". 
    - The command is: `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
    - So from now on, you can write `sail` or `./vendor/bin/sail`
- Once the previous command has finished installing, in another terminal, execute `./vendor/bin/sail artisan key:generate`
- Execute `./vendor/bin/sail artisan migrate`
- Open the app using your browser in localhost

## Usage

Simply write a new task and hit the "Start" button. You'll see the timer starting to count. When you finish doing your task, hit the "Stop" button and this task will be saved into the database. To see your task history, refresh the page.

There's also the option of using this app with the terminal. There's two commands you can use:
- `./vendor/bin/sail artisan task:make {task_name} {action}`
- `./vendor/bin/sail artisan task:show`

When using the task:make command, you need to insert the task name and the action to perform. If the task you're about to run has multiple words (E.g.: a task called "make dinner"), you must use quotation marks to indicate the task name to the command. There's only two actions allowed: **start**, which will start the time counter and **end** which will end it.

Some examples of usage:
- `./vendor/bin/sail artisan task:make cook start`
- `./vendor/bin/sail artisan task:make "drink water" end`

The task:show command doesn't need any input, it will return a table of all the tasks and their names, start time, end time, elapsed time and status. After the table, it also shows you the total elapsed time.

## Some problems you may encounter

If you start the project without migrating the DB, there will be errors related to that. To fix them:
- `./vendor/bin/sail down --rmi all -v`
- `./vendor/bin/sail up`

If there's an error involving ports used by other applications, you may change them from the .env file. For example:
- APP_PORT=8080
