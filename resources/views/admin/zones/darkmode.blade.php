@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

<?php
// echo $value2;
        $servername = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
        $sql = "UPDATE darkmode SET darkmode='$value2' WHERE id=1";
        
        if (mysqli_query($conn, $sql)) {
            // echo "Record updated successfully";
        
        
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        
        // die();
        
        // die();
        // echo $counter2;
        
?>
    <meta http-equiv="refresh" content="1; url=/admin/home" />
