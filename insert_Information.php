<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Information</title>
    <link rel="stylesheet" href="css/insert_Information.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <div class="profile-pic">
            <img src="images/logo_vertical.png" alt="Profile Picture">
        </div>
        <form action="submit_form.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="company">Company Name:</label>
                <input type="text" id="company" name="company" required>
            </div>
        </form>
        <div class="button-group">
            <button type="button">
                <i class="fas fa-upload"></i>
            </button>
            <button type="button">
                <i class="fas fa-qrcode"></i>
            </button>
            <button type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</body>
</html>
