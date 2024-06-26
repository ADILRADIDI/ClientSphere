<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bodySignUp">
    <div class="container">
        <div><img id="imgSignUp" src="./images/backgroundSignUP.jpg" alt=""></div>
        <div>
            <h3>welcome to ClientSphere</h3>
            <p>already have an account?<a id="linkLogin" href="login.php">login</a></p>
            <form id="form1" method="post" action="" enctype="multipart/form-data">
                <div  class="divInt">
                    <label id="inptlabel" for="email">Email</label>
                    <input id="inpt" type="email" name="email" placeholder="email">
                </div>
                <div class="divInt">
                    <label id="inptlabel" for="username">Username</label>
                    <input id="inpt" type="text" name="username" placeholder="username">
                </div>
                <div class="divInt">
                    <label id="inptlabel" for="password">Password</label>
                    <input id="inpt" type="password" name="password" placeholder="password">
                </div>
                <!-- <div class="divInt">
                    <label id="inptlabel" for="picture">Your picture</label>
                    <input id="inpt" type="file" name="picture" placeholder="picture">
                </div> -->
                
                <button id="btnSUBMIT" type="submit">Submit</button>
            </form>
        </div>
        
    </div>
</body>
<?php
require 'DB_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date = date('m/d/Y');
    
    // Handle f,u
    // $file_name = $_FILES['picture']['name'];
    // $file_tmp = $_FILES['picture']['tmp_name'];
    // $file_type = $_FILES['picture']['type'];
    // $folder = "./images/" . $file_name;
    // upload in  destin
    // move_uploaded_file($file_tmp, $folder);

    $stmt = $conn->prepare("INSERT INTO users (id, email, username, password, date) 
    VALUES (NULL, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("ssss", $email, $username, $password, $date);
        if ($stmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
    }
}
?>
</html>