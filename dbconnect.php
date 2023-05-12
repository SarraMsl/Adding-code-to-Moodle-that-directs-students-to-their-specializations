<?php
    $host = 'localhost';
    $username = 'postgres';
    $password = 'root123';
    $dbname = 'moodle';

    // Create connection
    $conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }
?>
