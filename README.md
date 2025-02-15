Product CSV Importer

This project provides a command-line tool to import product data from a CSV file into a Symfony-based application. The tool processes each product, applying validation rules, and inserts the data into the database.
Features

    Import products from a CSV file.
    Skip invalid products based on pre-defined rules (e.g., low stock, high cost).
    Handle discontinued products by setting their Discontinued date to the current date.
    Generate a detailed report of the import process.
    Support for "test" mode, where data is processed without being inserted into the database.

Requirements

    Symfony 7.x or later
    Doctrine ORM
    PHP 8.4 or higher
    MySQL database

Installation
Step 1: Clone the repository

First, clone this repository to your local machine.

git clone [git@github.com:teratronik/test-tsk.git](https://github.com/teratronik/test-tsk.git)
cd test-tsk

Step 2: Install dependencies

Install the project dependencies using Composer.

composer install

Step 3: Configure the database

Ensure that your database connection is properly set up. You can configure it in the .env or .env.local file.

DATABASE_URL="mysql://db_user:db_password@localhost:3306/db_name"

Step 4: Import Database from file make_database.sql

Step 5: Update the database schema

Run the following command to ensure the database schema is up-to-date.

php bin/console doctrine:schema:update --force

Usage
Import Products from CSV

To import products from a CSV file, run the following command:

php bin/console app:import-csv /path/to/your/file.csv

This command will:

    Parse the CSV file.
    Validate product data based on pre-defined import rules.
    Insert valid products into the database.
    Print a report summarizing the import results.

Test Mode

If you want to test the import without actually inserting data into the database (useful for dry runs), you can use the test flag:

php bin/console app:import-csv /path/to/your/file.csv test

Import Rules

The following import rules are applied:

    Products with a cost less than $5 or greater than $1000 will be skipped.
    Products with a stock less than 10 will be skipped.
    Discontinued products will be imported, but their Discontinued date will be set to the current date.
    A detailed report will be generated showing how many items were processed, how many were successful, and how many were skipped.

Example CSV File Format

The expected CSV format is as follows:

Product Code,Product Name,Product Description,Stock,Cost in GBP,Discontinued
P0001,TV,32” TV,10,399.99,
P0002,Cd Player,Nice CD player,11,50.12,yes
P0003,VCR,Top notch VCR,12,39.33,yes
...
