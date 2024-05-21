<?php
    $servername = "localhost";
    $username="root";
    $password='';
    $dbname='db_clientsphere';
    $conn = new mysqli($servername,$username,$password,$dbname);

    if($conn->connect_error){
        die("failed connected".$conn->connect_error);
    }

?>