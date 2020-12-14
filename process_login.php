<?php
    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $sql = "SELECT EXISTS (SELECT * FROM user WHERE title='')" 
?>