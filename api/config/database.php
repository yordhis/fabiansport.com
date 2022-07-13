<?php

    $host = DATABASE['host'];
    $database = DATABASE['database'];
    $username = DATABASE['username'];
    $password = DATABASE['password']; 
    $pdo = new PDO (
        "mysql:host=$host;dbname=$database",
        "$username",
        "$password"
    );
