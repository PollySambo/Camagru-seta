<?php

session_start();
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/database.php';

try {

    $id = $_GET['image_id'];
            $con = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM images WHERE image_id= '".$id."'";
  
    // use exec() because no results are returned
    $con->exec($sql);
    echo "Record deleted successfully";
    header('Location: gallery.php');
    
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
?>