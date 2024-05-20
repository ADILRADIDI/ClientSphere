<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bodylogin">
    <div class="container">

        <div class="box">
            <h3>welcome to ClientSphere</h3>
            <p>i dont have an account <a id="linkLogin" href="signUp.php">signUp</a></p>
            <form id="form2" method="post" action="home.php">
            <div class="divInt">
                    <label id="inptlabel2" for="email">Email</label>
                    <input id="inpt" type="email" name="email" placeholder="email">
            </div>
            <div class="divInt">
                    <label id="inptlabel2" for="password">Password</label>
                    <input id="inpt" type="password" name="password" placeholder="password">
                    </div>
                    <button id="btnSUBMIT" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
<?php
require 'DB_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            header("Location: home.php");
            exit(); 
        } else {
            echo "<p style='color: red;'>Invalid email or password.</p>";
        }
    } else {
        echo "<p style='color: blue;'>Invalid email or password.</p>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

</html>