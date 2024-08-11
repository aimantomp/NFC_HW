<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['reset_password']) && isset($_POST['token']) && isset($_POST['new_password'])) {
    $token = $conn->real_escape_string($_POST['token']);
    $new_password = password_hash($conn->real_escape_string($_POST['new_password']), PASSWORD_DEFAULT);

    // Verify the token and get the associated email
    $sql = "SELECT email FROM password_resets WHERE token = '$token' AND expires_at > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];

        // Update the user's password
        $sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
        $conn->query($sql);

        // Delete the reset token
        $sql = "DELETE FROM password_resets WHERE token = '$token'";
        $conn->query($sql);

        echo "Password has been successfully updated.";
    } else {
        echo "Invalid or expired token.";
    }
}

$conn->close();
?>
