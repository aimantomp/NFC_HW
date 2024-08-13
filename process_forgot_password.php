<?php
session_start();

// Include PHPMailer classes
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $token = bin2hex(random_bytes(50));

    // Insert the token and email into the password_resets table
    $sql = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
    if ($conn->query($sql) === TRUE) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';  // Replace with your Gmail address
            $mail->Password = 'your-app-password';     // Replace with your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            // Recipients
            $mail->setFrom('your-email@example.com', 'Your Name');
            $mail->addAddress($email); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = 'Click <a href="http://yourdomain.com/reset_password.php?token=' . $token . '">here</a> to reset your password.';
            $mail->AltBody = 'Click the following link to reset your password: http://yourdomain.com/reset_password.php?token=' . $token;

            $mail->send();
            echo 'Reset email has been sent.';
        } catch (Exception $e) {
            echo 'Failed to send the reset email. Mailer Error: ', $mail->ErrorInfo;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
