<?php
// Start the session
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user information from the database using session user_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // Update session variables with fresh data from the database (optional)
    $_SESSION['name'] = $user['empname'];
    $_SESSION['ic'] = $user['ic'];
    $_SESSION['position'] = $user['position'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone_number'] = $user['phone_number'];
    $_SESSION['linkedin_link'] = $user['linkedin_link'];
} else {
    echo "User not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Card Front</title>
    <link rel="stylesheet" href="css/business_Card_Front.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="profile-card">
        <div class="profile-pic">
            <img src="images/logo_vertical.png" alt="Company Logo">
        </div>
        <div class="profile-detail">
            <p><strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></p>
            <p><strong><?php echo htmlspecialchars($_SESSION['position'] ?? 'Position Not Available'); ?></strong></p>
        </div>
        <div class="white-block"></div>
    </div>
    <div class="black-block"></div>
    <div class="detail-card">
        <p><strong><?php echo htmlspecialchars($_SESSION['email'] ?? 'Email Not Available'); ?></strong></p>
        <p><strong><?php echo htmlspecialchars($_SESSION['phone_number'] ?? 'Phone Not Available'); ?></strong></p>
    </div>
    <div class="button-group">
        <!-- LinkedIn Button with href to LinkedIn profile -->
        <a href="<?php echo htmlspecialchars($_SESSION['linkedin_link'] ?? '#'); ?>" target="_blank">
            <button class="linkedIn-button">
                <i class="fas fa-linkedin"></i> LinkedIn Profile
            </button>
        </a>
        <button class="share-button"><i class="fas fa-share"></i> Share</button>
    </div>
    <div class="icons">
        <a href="business_Card_Back.php">
            <div class="chevron-icon"><i class="fas fa-chevron-down"></i></div> 
        </a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
