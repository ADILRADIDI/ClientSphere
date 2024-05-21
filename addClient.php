<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'ADMIN'</title>
    <link rel="stylesheet" href="styles.css">
    <script src="main.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> 
    <style>
                #attention{
    color: red;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 400px ;
}
    </style>
</head>
<body>
    <!-- <h1>hi im here </h1> -->
    <?php
require 'DB_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO clients (name, email, phone) VALUES (?, ?, ?)");

    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("sss", $name, $email, $phone);
        if ($stmt->execute()) {
            echo '
            <div id="attention">
                <h1>Event add success!</h1>
                <a id="linkHero" href="Home.php">Return to Home</a>
            </div>';
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
    }
}
?>


</body>
</html>