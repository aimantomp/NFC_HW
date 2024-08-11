<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: adminmain.php");
        exit();
    } elseif ($_SESSION['role'] == 'user') {
        header("Location: userdashboard.php");
        exit();
    }
}

$conn = new mysqli('localhost', 'root', '', 'superadmin_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);

    // Check if the token is valid and not expired
    $sql = "SELECT email FROM password_resets WHERE token = '$token' AND expires_at > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];
    } else {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No token provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/testing.css">
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="wrap">
                    <div class="img" style="background-image: url(images/logo.png);"></div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <center><h3 class="mb-4">Reset Password</h3></center>
                            </div>
                        </div>
                        <form action="process_reset_password.php" method="POST" class="signin-form">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="form-group mt-3">
                                <input type="password" name="new_password" class="form-control" required>
                                <label class="form-control-placeholder" for="new_password">New Password</label>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="reset_password" class="form-control btn btn-primary rounded submit px-3">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
