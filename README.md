# Archive Application Command

## Description
This Artisan command is designed to archive the codebase and databases of a Laravel application. It offers various options to customize the archiving process.

## Installation

Ensure that you have installed all project dependencies and configured your Laravel environment:


composer install

cp .env.example .env

php artisan key:generate

Usage

The command can be used with several options:
Basic Options

    --include-db: Archives all databases.
    --exclude-db={database.table}: Excludes a specific table from archiving.
    --include-folder={folder}: Includes a specific folder in the archive that is excluded by default.
    --exclude-folder={folder}: Excludes a specific folder from the archive.
    --password={password}: Sets a password for the protected archives.

Execution Examples

Archive only the codebase:
php artisan app:archive --password=YourPassword

Archive the codebase and databases:
php artisan app:archive --include-db --password=YourPassword

Exclude specific folders and tables:
php artisan app:archive --include-db --exclude-db=your_database.your_table --exclude-folder=node_modules --password=YourPassword

Debugging

For debugging, check the Laravel logs or configure the .env file to include detailed logs:

LOG_CHANNEL=daily

LOG_LEVEL=debug

