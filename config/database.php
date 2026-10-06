<?php
/* =========================================
   DATABASE CONNECTION CONFIGURATION
   ========================================= */

// MySQL Database Credentials
$host     = 'localhost';
$dbname   = 'menTaiYa';
$username = 'root';
$password = '';


// Initialize PDO Connection with Error Handling and Default Fetch Settings
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);