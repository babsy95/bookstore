
<?php

    // define('DB_USER', 'root');
    // define('DB_PASSWORD', '');
    // define('DB_HOST', 'localhost');
    // define('DB_NAME', 'book-store');

    define('DB_USER', 'admin');
    define('DB_PASSWORD', 'babsy1234');
    define('DB_HOST', 'bookstore.cyucnklcjixy.us-east-2.rds.amazonaws.com');
    define('DB_PORT', '3306');
    define('DB_NAME', 'bookstore');

    $mysqli = new MySQLi(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_error) {
        echo $mysqli->connect_error;
        unset($mysqli);
    } else { 
        $mysqli->set_charset('utf8');
    } 


