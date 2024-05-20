<?php
    require 'DB_connect.php';

    // import data
    if (isset($_POST['import'])) {
        $filename = $_FILES['file']['tmp_name'];
        if ($_FILES['file']['size'] > 0) {
            // var_dump($_FILES);
            $file = fopen($filename, "r");
            while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
                $name = $data[0];
                $email = $data[1];
                $stmt = $conn->prepare("INSERT INTO clients (name, email) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $email);
                $stmt->execute();
            }
            fclose($file);
        }
    }

// select table and  Export data in csv
$select = "SELECT * FROM clients";
$result = $conn->query($select);

$clients = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}
if (isset($_POST['export'])) {
    $filename = "clients_" . date('md') . ".csv";
    $file = fopen($filename, 'w');
    
    $headers = array_keys($clients[0]);
    fputcsv($file, $headers);
    
    foreach ($clients as $client) {
        fputcsv($file, $client);
    }
    
    fclose($file);
    
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/csv");
    readfile($filename);
    
    unlink($filename);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client_Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="body">
<div class="container">
        <h1>Client_Management</h1>
        <img src="./images/download.jpg" alt="Image Description">
        <button class="logout-button">Log Out</button>
</div>
    <form method="post" enctype="multipart/form-data">
        <div class="buttons">
            <input id="inpt_file" type="file" name="file" id="file" accept=".csv" required>
            <button id="import" type="submit" name="import">+ Import CSV</button>
            
        </div>
    </form>
    <form method="post" enctype="multipart/form-data">
        <div class="buttons">
        <input id="da1" type="submit" name="export" value="Export CSV">
        </div>
    </form>

    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        <?php
    require 'DB_connect.php';

    $stmt = $conn->prepare("SELECT * FROM clients");
    $stmt-> execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <tr>
                <td>'.$row['idClient'].'</td>
                <td>'.$row['Name'].'</td>
                <td>'.$row['Email'].'</td>
        </tr>
            ';
        }
    } else {
        echo "No data found.";
    }
    ?>
        </tbody>
    </table>
    <!-- toggle btn import and export -->
    <!-- <script>
        let inpt = document.getElementById("inpt_file");
        let btnBlue = document.getElementById("import");
        btnBlue.addEventListener("click",()=>{
            inpt.style.display="block";
        })
    </script> -->
</body>
</html>