<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT empname, ic, gender, bank_name, bank_account_no, company_name, company_address, company_contact_no FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

// Check if the query returned the correct data
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // Store data in session
    $_SESSION['name'] = $user['empname'];
    $_SESSION['ic'] = $user['ic'];
    $_SESSION['gender'] = $user['gender'];
    $_SESSION['bank_name'] = $user['bank_name'];
    $_SESSION['bank_account_no'] = $user['bank_account_no'];
    $_SESSION['company_name'] = $user['company_name'];
    $_SESSION['company_address'] = $user['company_address'];
    $_SESSION['company_contact_no'] = $user['company_contact_no'];
} else {
    echo "Error: User data not found.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/userdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="profile-page">
        <div class="header"></div>
        <div class="profile-card"> 
            <div class="info-container">
                <div class="profile-pic">
                    <img src="images/profile.jpg" alt="Profile Picture">
                </div>  
                <div class="profile-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                    <p><strong>IC:</strong> <?php echo htmlspecialchars($_SESSION['ic'] ?? 'N/A'); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($_SESSION['gender'] ?? 'N/A'); ?></p>
                </div>
                <!-- <div class="bank-info">
                    <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($_SESSION['bank_name'] ?? 'N/A'); ?></p>
                    <p><strong>Bank Account No:</strong> <?php echo htmlspecialchars($_SESSION['bank_account_no'] ?? 'N/A'); ?></p>
                </div> -->
            </div>
        </div>
        <div class="company-info">
            <h2>Company Info</h2>
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($_SESSION['company_name'] ?? 'N/A'); ?></p>
            <p><strong>Company Address:</strong> <?php echo htmlspecialchars($_SESSION['company_address'] ?? 'N/A'); ?></p>
            <p><strong>Company Contact No:</strong> <?php echo htmlspecialchars($_SESSION['company_contact_no'] ?? 'N/A'); ?></p>
        </div>
        <hr>
        <div class="scan-qr">
            <a href="insert_Information.php"><i class="fas fa-qrcode"></i> Scan QR <i class="fas fa-chevron-right arrow-icon"></i></a> 
            <!-- <a href="#"><i class="fas fa-qrcode"></i> Scan QR <i class="fas fa-chevron-right arrow-icon"></i></a> -->
        </div>
        <hr>
        <div class="contact-list">
            <a href="contact_List.php"><i class="fas fa-address-book"></i> Contact List <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
        <hr>
        <div class="edit-info">
            <a href="info_Capture_OCR.php"><i class="fas fa-edit"></i> Edit Info <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
        <hr>
        <div class="log-out">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
    </div>
</body>
</html>

<?php
// print_r($_SESSION);
// exit();
?>