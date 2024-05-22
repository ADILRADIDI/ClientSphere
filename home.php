<?php
session_start();
$ses = $_SESSION['id'];
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
?>


<?php

    require 'DB_connect.php';
    $sessID = $_SESSION['id'];
    // import data
    if (isset($_POST['import'])) {
        $filename = $_FILES['file']['tmp_name'];
        if ($_FILES['file']['size'] > 0) {
            // var_dump($_FILES);
            $file = fopen($filename, "r");
            while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
                $name = $data[0];
                $email = $data[1];
                $phone = $data[2];
                $stmt = $conn->prepare("INSERT INTO clients (name, email,phone,userID) 
                VALUES (?, ?, ?, ?)");

                $stmt->bind_param("sssi", $name, $email,
                $phone,$sessID);
                $stmt->execute();
            }
            fclose($file);
        }
    }

// select table and  Export data in csv
$ID1 = $_SESSION['id'];
$select = "SELECT * FROM clients where userID = $ID1";
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .links{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    margin: 0 auto;
    gap: 30px;
    }
    #lk,.btn_addevent,#deleteLink,#updateLink{
        background-color: #007BFF;
        border-radius: 20px;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
    }
    #lK:hover{
        background-color: transparent;
        color: black;
        transition: 1.5s;
    }
    </style>
</head>
<body id="body">
<div class="container">
        <h1>Client_Management</h1>
        <!-- image from signUp -->
        <?php
// require 'DB_connect.php';
// if (isset($_POST['id'])) {
//     $idUser = mysqli_real_escape_string($conn, $_POST['id']);

//     $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
//     $stmt->bind_param("i", $idUser);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             echo '<img src="'.$row['picture'].'" alt="User Image">';
//         }
//     } else {
//         echo "No data found.";
//     }
// } else {
//     echo "ID not provided.";
// }
?>

        
    <form action="logout.php" method="post">
        <button type="submit" class="button">Logout</button>
    </form>
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
    <div class="links">
        <a href='#'><button type="button"
                data-toggle="modal" data-target="#exampleModal" class="btn_addevent">
                + ADD Client
                </button>
        </a>
        <!-- Update link -->
        <a id="updateLink" href="#" data-toggle="modal" 
        data-target="#updateModal">Update Client</a>
        <!-- Delete link -->
        <a id="deleteLink" href="#" data-toggle="modal" 
        data-target="#deleteModal">Delete Client</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>phone</th>
            </tr>
        </thead>
        <tbody>
        <?php
    require 'DB_connect.php';

    $stmt = $conn->prepare("SELECT * FROM clients WHERE userID = ?");
    $IDD = $_SESSION['id'];
    $stmt->bind_param("i", $IDD);
    $stmt-> execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <tr>
                <td>'.$row['idClient'].'</td>
                <td>'.$row['Name'].'</td>
                <td>'.$row['Email'].'</td>
                <td>'.$row['phone'].'</td>

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



    <!--  -->
    
<!-- modal update -->
<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="post" action="updateClient.php">
          <input type="text" name="id"  placeholder="ID client FOR UPDATE">
          <br>
          <input type="text" name="name"  placeholder="client Name">
          <br>
          <input type="text" name="email" placeholder="email">
          <br>
          <input type="text" name="phone"  placeholder="phone">
          <br>
          <input type="submit" class="btn btn-primary" value="Update">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!--  -->
<!-- MODAL CODE delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" 
role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="deleteForm" method="post" action="deleteClient.php">
          <input type="text" id="id-input" name="id" placeholder="Enter client ID">
          <br>
          <input type="submit" class="btn btn-danger" value="Delete">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
        </button>
      </div>
    </div>
  </div>
</div>


<!-- MODAL CODE -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Information for add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
<form id="postForm" method="post" action="addClient.php">
    <input type="text" name="name" placeholder="Client Name">
    <br>
    <input type="text" name="email" placeholder="Client Email">
    <br>
    <input type="text" name="phone" placeholder="Client Phone">
    <br>
    <input id="linkHero" type="submit" name="Submit" value="Submit">
    <br>
</form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>