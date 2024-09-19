<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'superadmin') {
        header("Location: adminmain.php"); // Redirect superadmin to adminmain.php
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header("Location: admin.php"); // Redirect admin to admin.php
        exit();
    } elseif ($_SESSION['role'] == 'user') {
        header("Location: userdashboard.php"); // Redirect user to userdashboard.php
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                                <center><h3 class="mb-4">Log In</h3></center>
                            </div>
                        </div>
                        <!-- Modified form action to point to process_login.php -->
                        <form action="process_login.php" method="POST" class="signin-form">
                            <div class="form-group mt-3">
                                <input type="email" name="email" class="form-control" required>
                                <label class="form-control-placeholder" for="email">Email</label>
                            </div>
                            <div class="form-group">
                                <input id="password-field" name="password" type="password" class="form-control" required>
                                <label class="form-control-placeholder" for="password">Password</label>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="login" class="form-control btn btn-primary rounded submit px-3">Log In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="forgot_password.php">Forgot Password</a>
                                </div>
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
