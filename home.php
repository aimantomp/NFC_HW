<?php
session_start();

if (!isset($_SESSION['empid'])) {
    echo "Unauthorized access.";
    exit;
}

include('connect.php');

$sql = "SELECT  empname, empphone, empemail FROM employee";  // Modify this query based on your table structure
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card user-card">
                <div class="card-header">
                    <h5>Profile</h5>
                </div>
                <div class="card-block" >
                    <div class="user-image">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class="img-radius" alt="User-Profile-Image">
                    </div>
                    <table>
       
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                
                echo "<tr>";
                echo "<th>Name</th>";
                echo "<td>" . $row["empname"]. "</td>";
                echo "<tr>";
                echo "<th>Phone</th>";
                echo "<td>" . $row["empphone"]. "</td>";
                echo "<tr>";
                echo "<th>Email</th>";
                echo "<td>" . $row["empemail"]. "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
                    <p class="m-t-15 text-muted">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <hr>
                    <div class="row justify-content-center user-social-link">
                        <div class="col-auto"><a href="#!"><i class="fa fa-facebook text-facebook"></i></a></div>
                        <div class="col-auto"><a href="#!"><i class="fa fa-twitter text-twitter"></i></a></div>
                        <div class="col-auto"><a href="#!"><i class="fa fa-dribbble text-dribbble"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
    </div>
</div>
</body>
</html>