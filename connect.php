<?php

//initalizing variables
$conn = null;
try{
    //Establish PDO Connection to the Database
    $host = 'localhost';
    $username = 'admin';
    $password = 'password123';
    $dbname = 'schema';
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
}catch(Exception $e){
    echo "Server Error 500: Failed to Exceute Operatiion";
    die();
}catch(Error $s){
    echo "Server Error 501: Failed to Exceute Operatiion";
    die();
}