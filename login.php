<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $_SESSION['empemail'] = $_POST['email'];
        $_SESSION['emppass'] = $_POST['password'];
    } else {
        echo "Email or Password is not set";
        exit;
    }
}

include('connect.php');

if (isset($_SESSION['empemail']) && isset($_SESSION['emppass'])) {
    $empemail = $conn->real_escape_string($_SESSION['empemail']);
    $emppass = $conn->real_escape_string($_SESSION['emppass']);

    $sql = "SELECT * FROM employee WHERE empemail = '$empemail' AND emppass = '$emppass'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    if ($result->num_rows == 0) {
        echo "<center><h1>Login Fail</h1></center>";
        session_unset();
        echo "<meta http-equiv=\"refresh\" content=\"3;URL=../index.html\">";
    } else {
        $_SESSION['empid'] = $row['userID'];
        header('Location: home.php');
    }

    $conn->close();
} else {
    echo "Session variables are not set.";
    exit;
}
?>