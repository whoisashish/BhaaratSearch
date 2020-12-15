<?php

ob_start();

try{
    $con = new PDO("mysql:dbname=bhaarat;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch(PDOExeption $e){
    echo "Connection Failed: " . $e->getMessage();
}

?>