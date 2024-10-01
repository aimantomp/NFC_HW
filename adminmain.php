<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "superadmin_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'superadmin') {

    header("Location: login.php");
    exit();
}

// Check if the user's name is available in the session
$username = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';

// Handle Add User
if (isset($_POST['add_user'])) {
    $name = $conn->real_escape_string($_POST['empname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);
    $company = $conn->real_escape_string($_POST['company_name']);
    $created_at = date('Y-m-d');
    $created_time = date('H:i:s');

    $sql = "INSERT INTO users (empname, username, email, password, role, company_name) 
            VALUES ('$name', '$username', '$email','$password', '$role', '$company')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "New user added successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

// Handle Edit User
if (isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['empname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $company = $conn->real_escape_string($_POST['company_name']);

    $sql = "UPDATE users SET empname='$name', username='$username', email='$email', role='$role', company_name='$company' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "User updated successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM users WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

// Fetch all users for display
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="adminmain.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="images/logo.png" alt="Logo" width="150">
        </div>
        <a href="admin.php">Dashboard Admin</a>
        <a href="#">Add User</a>
        <a href="qr_phonebook.php">QR Phone Book</a>
        <a href="#">Edit User</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <h3 class="text-black"><b>Dashboard</b></h3>
        <div class="dropdown">
        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Welcome, <?php echo htmlspecialchars($username); ?>!
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#">Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Logout</a>
        </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container">
            <!-- Display any messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Add User Button -->
            <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addUserModal">
                Add User
            </button>

            <!-- User Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['empname']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><span class="badge badge-secondary"><?php echo $row['role']; ?></span></td>
                                    <td><?php echo $row['company_name']; ?></td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-primary edit-btn"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-empname="<?php echo $row['empname']; ?>"
                                            data-username="<?php echo $row['username']; ?>"
                                            data-email="<?php echo $row['email']; ?>"
                                            data-role="<?php echo $row['role']; ?>"
                                            data-company="<?php echo $row['company_name']; ?>"
                                            data-toggle="modal"
                                            data-target="#editUserModal"
                                        >Edit</button>
                                    </td>
                                    <td><a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="empname">Name</label>
                            <input type="text" class="form-control" name="empname" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="company_name">Company</label>
                            <input type="text" class="form-control" name="company_name" required>
                        </div>
                        <div class="text-right">
                        <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-empname">Name</label>
                            <input type="text" class="form-control" name="empname" id="edit-empname" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-username">Username</label>
                            <input type="text" class="form-control" name="username" id="edit-username" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" class="form-control" name="email" id="edit-email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-role">Role</label>
                            <select name="role" id="edit-role" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-company">Company</label>
                            <input type="text" class="form-control" name="company_name" id="edit-company" required>
                        </div>
                        <div class="text-right">
                        <button type="submit" class="btn btn-primary" name="edit_user">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Edit button click event
            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                var empname = $(this).data('empname');
                var username = $(this).data('username');
                var email = $(this).data('email');
                var role = $(this).data('role');
                var company = $(this).data('company');

                // Set values in the edit form
                $('#edit-id').val(id);
                $('#edit-empname').val(empname);
                $('#edit-username').val(username);
                $('#edit-email').val(email);
                $('#edit-role').val(role);
                $('#edit-company').val(company);
            });
        });
    </script>
</body>
</html>
