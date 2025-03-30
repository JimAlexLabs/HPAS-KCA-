# Running the Hospital Management System in Laravel Style

This guide explains how to run the Hospital Management System using a Laravel-style approach, without Docker, Apache, or XAMPP servers.

## Prerequisites

- PHP 7.2 or higher
- MySQL 5.7 or higher
- Basic understanding of terminal/command line

## Getting Started

We've added several Laravel-like features to make running this traditional PHP application feel more modern:

1. An artisan-like command line interface
2. A Laravel-style router
3. A development server similar to `php artisan serve`

## Setup Instructions

### 1. Database Setup

You'll need to set up the MySQL database first:

```bash
# Login to MySQL (replace 'your_password' with your actual MySQL password)
mysql -u root -p

# Then in the MySQL prompt, create the database
CREATE DATABASE myhmsdb;
USE myhmsdb;
exit;

# Import the database schema and data
mysql -u root -p myhmsdb < myhmsdb.sql
```

### 2. Update Database Connection

If your MySQL uses a different username or password than the default (`root` with no password), you'll need to update the connection settings:

- Open `func.php` and similar files
- Update this line: `$con=mysqli_connect("localhost","root","","myhmsdb");`
- Change to: `$con=mysqli_connect("localhost","your_username","your_password","myhmsdb");`

### 3. Running the Application

Use the Laravel-style artisan command:

```bash
# Start the development server
./artisan serve

# Display help
./artisan help
```

The application will be available at: http://localhost:8080

### 4. Login Credentials

**Admin Login:**

- Username: admin
- Password: admin000

**Doctor Login (example):**

- Username: Jimal
- Password: jimal123

**Patient Login (example):**

- Email: kimjustin@gmail.com
- Password: kim123

## How It Works

The Laravel-style setup consists of:

1. `artisan` - A command-line tool similar to Laravel's artisan
2. `serve.php` - A development server script
3. `index.router.php` - A front controller that handles routing

This provides a more modern development experience without changing the underlying codebase.

## For Your Lecture

When demonstrating this to a lecture audience:

1. Show how the artisan command works (`./artisan help`)
2. Demonstrate starting the server (`./artisan serve`)
3. Walk through the main features of the Hospital Management System
4. Explain the different user roles (admin, doctor, patient)
5. Show how the Laravel-style router handles requests

The Laravel-style approach gives a cleaner, more modern feel to the project while keeping the original codebase intact.
