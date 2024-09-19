<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
          
            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['name'] = $row['name'];

                // Redirect based on role
                if ($row['role'] == 'superadmin') {
                    header("Location: adminmain.php"); // Redirect superadmin to adminmain.php
                } elseif ($row['role'] == 'admin') {
                    header("Location: admin.php"); // Redirect admin to admin.php
                } elseif ($row['role'] == 'user') {
                    header("Location: userdashboard.php"); // Redirect user to userdashboard.php
                } else {
                    echo "Role not recognized."; // In case an unexpected role is found
                }
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that email.";
        }
    }
}

$conn->close();
?>
