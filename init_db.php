<?php
$con = mysqli_connect("localhost", "root", "password123");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Create the database if it doesn't exist
$query = "CREATE DATABASE IF NOT EXISTS myhmsdb";
if (mysqli_query($con, $query)) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . mysqli_error($con) . "<br>";
}

// Select the database
mysqli_select_db($con, "myhmsdb");

// Create the necessary tables if they don't exist
$tables = [
    "admintb" => "CREATE TABLE IF NOT EXISTS admintb (
        username VARCHAR(50) PRIMARY KEY,
        password VARCHAR(50)
    )",
    
    "doctb" => "CREATE TABLE IF NOT EXISTS doctb (
        username VARCHAR(50) PRIMARY KEY,
        password VARCHAR(50),
        email VARCHAR(50),
        spec VARCHAR(50),
        docFees INT
    )",
    
    "patreg" => "CREATE TABLE IF NOT EXISTS patreg (
        pid INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(50),
        lname VARCHAR(50),
        gender VARCHAR(10),
        email VARCHAR(50),
        contact VARCHAR(50),
        password VARCHAR(50),
        cpassword VARCHAR(50)
    )",
    
    "appointmenttb" => "CREATE TABLE IF NOT EXISTS appointmenttb (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        pid INT,
        fname VARCHAR(50),
        lname VARCHAR(50),
        gender VARCHAR(10),
        email VARCHAR(50),
        contact VARCHAR(50),
        doctor VARCHAR(50),
        docFees INT,
        appdate DATE,
        apptime TIME,
        userStatus INT,
        doctorStatus INT
    )",
    
    "prestb" => "CREATE TABLE IF NOT EXISTS prestb (
        ID INT,
        pid INT,
        fname VARCHAR(50),
        lname VARCHAR(50),
        doctor VARCHAR(50),
        appdate DATE,
        apptime TIME,
        disease VARCHAR(100),
        allergy VARCHAR(100),
        prescription VARCHAR(255)
    )",
    
    "contact" => "CREATE TABLE IF NOT EXISTS contact (
        name VARCHAR(50),
        email VARCHAR(50),
        contact VARCHAR(50),
        message VARCHAR(255)
    )"
];

// Create each table
foreach ($tables as $table => $sql) {
    if (mysqli_query($con, $sql)) {
        echo "Table $table created successfully or already exists<br>";
    } else {
        echo "Error creating table $table: " . mysqli_error($con) . "<br>";
    }
}

// Insert default admin user if not exists
$adminCheck = mysqli_query($con, "SELECT * FROM admintb WHERE username='admin'");
if (mysqli_num_rows($adminCheck) == 0) {
    $adminInsert = "INSERT INTO admintb (username, password) VALUES ('admin', 'admin000')";
    if (mysqli_query($con, $adminInsert)) {
        echo "Default admin user created<br>";
    } else {
        echo "Error creating default admin: " . mysqli_error($con) . "<br>";
    }
}

echo "<br>Database initialization complete! <a href='index.php'>Go to homepage</a>";
?> 