<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Fetch user data from the database based on the provided username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the provided password with the stored hashed password
        if (password_verify($password, $user['password'])) {
            // Set session variables with all user information
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['empname'];
            $_SESSION['ic'] = $user['ic'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['bank_name'] = $user['bank_name'];
            $_SESSION['bank_account_no'] = $user['bank_account_no'];
            $_SESSION['company_name'] = $user['company_name'];
            $_SESSION['company_address'] = $user['company_address'];
            $_SESSION['company_contact_no'] = $user['company_contact_no'];

            // Redirect to the user dashboard
            header("Location: userdashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="profile.css">

</head>
<body>
    <div class="profile-page">
        <div class="header">
            <div class="profile-pic">
                <img src="images/profile.png" alt="Profile Picture">
            </div>
        </div>
        <div class="profile-card">
            <div class="info-container">
                <div class="profile-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                    <p><strong>IC:</strong> <?php echo htmlspecialchars($_SESSION['ic'] ?? 'N/A'); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($_SESSION['gender'] ?? 'N/A'); ?></p>
                </div>
                <div class="bank-info">
                    <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($_SESSION['bank_name'] ?? 'N/A'); ?></p>
                    <p><strong>Bank Account No:</strong> <?php echo htmlspecialchars($_SESSION['bank_account_no'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
        <div class="company-info">
            <h2>Company Info</h2>
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($_SESSION['company_name'] ?? 'N/A'); ?></p>
            <p><strong>Company Address:</strong> <?php echo htmlspecialchars($_SESSION['company_address'] ?? 'N/A'); ?></p>
            <p><strong>Company Contact No:</strong> <?php echo htmlspecialchars($_SESSION['company_contact_no'] ?? 'N/A'); ?></p>
        </div>
        <div class="scan-qr">
            <a href="#"><i class="fas fa-qrcode"></i> Scan QR <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
        <div class="contact-list">
            <a href="contactlist.html"><i class="fas fa-address-book"></i> Contact List <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
        <div class="edit-info">
            <a href="#"><i class="fas fa-edit"></i> Edit Info <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
        <div class="log-out">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
    </div>
</body>
</html>
