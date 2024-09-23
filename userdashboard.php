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
    $_SESSION['email'] = $user['email'];
    $_SESSION['position'] = $user['position'];
    $_SESSION['company_name'] = $user['company_name'];
    $_SESSION['company_address'] = $user['company_address'];
    $_SESSION['company_contact_no'] = $user['company_contact_no'];
    $_SESSION['profile_pic'] = $user['profile_pic'] ?? 'images/default_profile.jpg'; // Use default image if not set
} else {
    echo "User not found.";
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_dir = 'images/';
    $file_name = basename($_FILES['profile_pic']['name']);
    $upload_file = $upload_dir . $file_name;
    $image_file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['profile_pic']['tmp_name']);
    if ($check !== false) {
        // Check file size (limit to 2MB)
        if ($_FILES['profile_pic']['size'] < 2000000) {
            // Allow certain file formats
            if ($image_file_type == "jpg" || $image_file_type == "jpeg" || $image_file_type == "png" || $image_file_type == "gif") {
                // Move file to the designated folder
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_file)) {
                    // Update user profile picture in the database
                    $sql_update = "UPDATE users SET profile_pic='$upload_file' WHERE id=$user_id";
                    if ($conn->query($sql_update) === TRUE) {
                        $_SESSION['profile_pic'] = $upload_file; // Update session variable
                        echo "Profile picture uploaded successfully.";
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "Sorry, your file is too large.";
        }
    } else {
        echo "File is not an image.";
    }
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
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">
                </div> 
                 <!-- Upload Profile Picture Form -->
                <form action="userdashboard.php" method="post" enctype="multipart/form-data">
                    <label for="profile_pic">Upload Profile Picture:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                    <button type="submit">Upload</button>
                </form> 
                <div class="profile-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name'] ?? 'N/A'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['position'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <div class="company-info">
            <h2>Company Info</h2>
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($_SESSION['company_name'] ?? 'N/A'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($_SESSION['company_address'] ?? 'N/A'); ?></p>
            <p><strong>Contact No:</strong> <?php echo htmlspecialchars($_SESSION['company_contact_no'] ?? 'N/A'); ?></p>
        </div>

        <div class="menu-section">
            <a href="scan_QR.php" class="menu-item"><i class="fas fa-qrcode"></i> Generate QR <i class="fas fa-chevron-right arrow-icon"></i></a>
            <hr>
            <a href="contact_List.php" class="menu-item"><i class="fas fa-address-book"></i> Contact List <i class="fas fa-chevron-right arrow-icon"></i></a>
            <hr>
            <a href="business_Card_Front.php" class="menu-item"><i class="fas fa-edit"></i> Edit Info <i class="fas fa-chevron-right arrow-icon"></i></a>
            <hr>
            <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Log Out <i class="fas fa-chevron-right arrow-icon"></i></a>
        </div>
    </div>

    <!-- Add any additional scripts if needed -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Full jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
