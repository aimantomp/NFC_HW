<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR Scanner</title>
    <link rel="stylesheet" href="css/scan_OCR.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="scanner-container">
        <div class="scanner-overlay">
            <div class="scanner-frame">
                <div class="scanner-line"></div>
            </div>
        </div>
        <form action="info_Capture_OCR.php">
            <button type="submit" class="snap-button"><i class="fas fa-camera"></i> Snap Picture</button>
        </form>
        <div class="instructions">
            <p>Align the document within the frame and click "Snap Picture" to capture.</p>
        </div>
    </div>
</body>
</html>
