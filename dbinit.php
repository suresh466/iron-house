<?php

// user root and pass empty for the connection just so getting started is easier
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

// try connecting to the server
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD)
    or die('Could not connect to the server: ' . mysqli_connect_error());

// create the database
if (mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS iron_house")) {
    echo "Database created successfully";
} else {
    die("Error creating database, maybe it exists already " . mysqli_error($conn));
}

// create the table.
$create_ironsuits_table = "CREATE TABLE ironsuits (
    ironsuit_id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ironsuit_name VARCHAR(30) NOT NULL,
    ironsuit_description TEXT NOT NULL,
    ironsuit_quantity_available INT NOT NULL,
    ironsuit_price DECIMAL(10, 2) NOT NULL,
    product_added_by VARCHAR(50) DEFAULT 'Suresh Thagunna'
    )";

mysqli_query($conn, $create_ironsuits_table);

// add initial data
$insert_ironsuits = "INSERT INTO ironsuits (ironsuit_name, ironsuit_description, ironsuit_quantity_available, ironsuit_price) VALUES
    ('Mark 1', 'The very first iron suit that ever existed, only 1 of a kind, and you can have it. while it might not have all the best tech, it brings back memories.', 1, 1000.00),
    ('Mark 2', 'The second iron suit, with a lot of improvements, and a lot of new tech. It is a must have for any collector.', 2, 40.00),
    ('Mark 3', 'This is the ironsuits tech made major breakthroughs, with advanced AI capabilities and supersonic flight speeds accopmanied by an advanced AI, Jarvis.', 30, 69.00),
    ('Mark 4', 'Now comes the fourth generation with compact form, truly powerful weapon system which can take over alien ships, an truly industrial design', 22, 75.00),
    ('Mark 5', 'The holy grail of iron suits, the ultimate Mark 5, this is where nano technology comes in, making the suit a part of you', 15, 85.00)
    ";