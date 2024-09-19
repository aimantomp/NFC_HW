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

    // Get the logo path from the database
    $sql = "SELECT company_logo FROM companies WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $logo_path = $row['company_logo'];

        // Delete the record from the database
        $sql = "DELETE FROM companies WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Delete the logo file from the server
            if (file_exists($logo_path) && !is_dir($logo_path)) {
                unlink($logo_path);
            }
            $notification_message = "Company deleted successfully!";
            $notification_type = "success";
        } else {
            $notification_message = "Error deleting company: " . $conn->error;
            $notification_type = "error";
        }
    } else {
        $notification_message = "Company not found.";
        $notification_type = "error";
    }

    // Redirect after delete operation
    header("Location: company_register.php");
    exit();
}

// Handle add company request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_company'])) {
    $company_name = $conn->real_escape_string($_POST['company_name']);
    $company_address = $conn->real_escape_string($_POST['company_address']);
    $company_logo = "";

    // Handle file upload
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] == 0) {
        $file_name = basename($_FILES['company_logo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file)) {
            $company_logo = $target_file;
        } else {
            $notification_message = "Failed to upload logo.";
            $notification_type = "error";
        }
    }

    $stmt = $conn->prepare("INSERT INTO companies (company_name, company_address, company_logo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $company_name, $company_address, $company_logo);

    if ($stmt->execute()) {
        $notification_message = "New company added successfully!";
        $notification_type = "success";
    } else {
        $notification_message = "Error: " . $stmt->error;
        $notification_type = "error";
    }

    // Redirect after adding company
    header("Location: company_register.php");
    exit();
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$offset = $page * $limit;

// Search setup
$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch companies with pagination and search
$sql = "SELECT id, company_name, company_address, company_logo FROM companies WHERE company_name LIKE '%$search_term%' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) AS total FROM companies WHERE company_name LIKE '%$search_term%'";
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
    <title>Company Registration</title>
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

    <h1>Register a New Company</h1>

    <!-- Add Company Form -->
    <div class="business-card-table">
        <h2>Add New Company</h2>
        <form method="POST" action="" enctype="multipart/form-data" style="width: 100%">
            <input type="text" name="company_name" placeholder="Company Name" required>
            <textarea name="company_address" placeholder="Company Address" required></textarea>
            <input type="file" name="company_logo">
            <button type="submit" name="add_company">Add Company</button>
        </form>

        <!-- Search and Filter Form -->
        <h2>Search Companies</h2>
        <form method="GET" action="">
            <input type="text" name="search" id="search" placeholder="Search by company name" value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Company List -->
        <h2>Company List</h2>
        <table>
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Company Address</th>
                    <th>Company Logo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['company_name'] . "</td>";
                        echo "<td>" . $row['company_address'] . "</td>";
                        echo "<td><img src='" . $row['company_logo'] . "' alt='Logo' width='100'></td>";
                        echo "<td><a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this company?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No companies found</td></tr>";
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

    <!-- Notification Popup -->
    <div class="cd-popup" role="alert">
        <div class="cd-popup-container">
            <p id="notification-message"></p>
            <ul class="cd-buttons">
                <li><a href="#0" class="cd-popup-close">Close</a></li>
            </ul>
            <a href="#0" class="cd-popup-close img-replace">Close</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="admin.js"></script>
    <script>
        var notificationMessage = "<?php echo addslashes($notification_message); ?>";
        var notificationType = "<?php echo $notification_type; ?>";

        $(document).ready(function() {
            if (notificationMessage) {
                $('#notification-message').text(notificationMessage);
                $('.cd-popup').addClass('is-visible');
            }

            $('.cd-popup-close').on('click', function(event){
                event.preventDefault();
                $('.cd-popup').removeClass('is-visible');
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
