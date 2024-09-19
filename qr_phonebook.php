<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$notification_message = "";
$notification_type = "";

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get the image path from the database
    $sql = "SELECT qr_image FROM qrcodes WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['qr_image'];

        // Delete the record from the database
        $sql = "DELETE FROM qrcodes WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Delete the image file from the server
            if (file_exists($image_path) && !is_dir($image_path)) {
                unlink($image_path);
            }
            $notification_message = "QR Code deleted successfully!";
            $notification_type = "success";
        } else {
            $notification_message = "Error deleting QR Code: " . $conn->error;
            $notification_type = "error";
        }
    } else {
        $notification_message = "QR Code not found.";
        $notification_type = "error";
    }
}

// Add QR Code with Image Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_qr'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $company_name = $conn->real_escape_string($_POST['company_name']);
    $company_address = $conn->real_escape_string($_POST['company_address']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);

    // Handle file upload
    $target_dir = "uploads/";
    $qr_image = "";
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] == 0) {
        $target_file = $target_dir . basename($_FILES["qr_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["qr_image"]["tmp_name"]);
        if ($check !== false) {
            // Attempt to move uploaded file
            if (move_uploaded_file($_FILES["qr_image"]["tmp_name"], $target_file)) {
                $qr_image = $target_file;
            } else {
                $notification_message = "Sorry, there was an error uploading your file.";
                $notification_type = "error";
            }
        } else {
            $notification_message = "File is not an image.";
            $notification_type = "error";
        }
    }

    // Insert data into database
    $sql = "INSERT INTO qrcodes (name, company_name, company_address, phone_number, qr_image) 
            VALUES ('$name', '$company_name', '$company_address', '$phone_number', '$qr_image')";

    if ($conn->query($sql) === TRUE) {
        $notification_message = "New QR Code added successfully!";
        $notification_type = "success";
    } else {
        $notification_message = "Error: " . $conn->error;
        $notification_type = "error";
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$offset = $page * $limit;

// Search setup
$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch QR Codes with pagination and search
$sql = "SELECT * FROM qrcodes WHERE name LIKE '%$search_term%' OR company_name LIKE '%$search_term%' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) AS total FROM qrcodes WHERE name LIKE '%$search_term%' OR company_name LIKE '%$search_term%'";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="adminmain.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Phonebook</title>
</head>
<body>
    <div class="top-nav-section">
        <div class="menu-nav-bar">
            <nav>
                <ul>
                    <li><a href="adminmain.php">Menu</a></li>
                    <li><a href="company_register.php">Register Company</a></li>
                    <li><a href="qr_phonebook.php">QR Phonebook</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="adminmain.php">Dummy</a></li>
                    <li><img src="logo.png" id="logo" /></li>
                </ul>
            </nav>
        </div>
    </div>

    <h1>QR Phonebook</h1>

    <!-- Add QR Code Form -->
    <div class="qr-code-form">
        <h2>Add New QR Code</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="company_name" placeholder="Company Name" required>
            <input type="text" name="company_address" placeholder="Company Address">
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="file" name="qr_image" accept="image/*" required>
            <button type="submit" name="add_qr">Add QR Code</button>
        </form>
    </div>

    <!-- Search and Filter Form -->
    <div class="search-form">
        <h2>Search QR Codes</h2>
        <form method="GET" action="">
            <input type="text" name="search" id="search" placeholder="Search by name or company name" value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- QR Codes List -->
    <div class="qr-codes-list">
        <h2>List of QR Codes</h2>
        <table>
            <thead>
                <tr>
                    <th>QR Code Image</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Company Address</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='" . $row['qr_image'] . "' alt='QR Code' width='100'></td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['company_name'] . "</td>";
                        echo "<td>" . $row['company_address'] . "</td>";
                        echo "<td>" . $row['phone_number'] . "</td>";
                        echo "<td><a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No QR codes found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="pagination">
            <?php if ($page > 0): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_term); ?>">Previous</a>
            <?php endif; ?>
            <?php if ($page < $total_pages - 1): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_term); ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="admin.js"></script>
</body>
</html>

<?php $conn->close(); ?>
