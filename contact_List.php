<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <link rel="stylesheet" href="css/contact_List.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h2>Contact List</h2>
    <div class="contact-list">
        <div class="contact-item">
            <div class="number-circle">1</div>
            <div class="contact-info">
                <div class="name">John Doe</div>
                <div class="company-name">Acme Corp</div>
                <div class="contact-details">
                    <p><strong>Name:</strong> John Doe</p>
                    <p><strong>Company:</strong> Acme Corp</p>
                    <p><strong>Email:</strong> john.doe@example.com</p>
                    <div class="button-group">
                        <button class="remove-button"><i class="fas fa-trash"></i> Remove</button>
                        <button class="save-button"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            <div class="icons">
                <div class="phone-icon"><i class="fas fa-phone"></i></div>
                <div class="chevron-icon"><i class="fas fa-chevron-down"></i></div>
            </div>
        </div>
        <div class="contact-item">
            <div class="number-circle">2</div>
            <div class="contact-info">
                <div class="name">Jane Smith</div>
                <div class="company-name">Tech Innovators</div>
                <div class="contact-details">
                    <p><strong>Name:</strong> Jane Smith</p>
                    <p><strong>Company:</strong> Tech Innovators</p>
                    <p><strong>Email:</strong> jane.smith@example.com</p>
                    <div class="button-group">
                        <button class="remove-button"><i class="fas fa-trash"></i> Remove</button>
                        <button class="save-button"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            <div class="icons">
                <div class="phone-icon"><i class="fas fa-phone"></i></div>
                <div class="chevron-icon"><i class="fas fa-chevron-down"></i></div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.chevron-icon').forEach(function(chevron) {
            chevron.addEventListener('click', function() {
                const contactDetails = this.closest('.contact-item').querySelector('.contact-details');
                contactDetails.classList.toggle('visible');

                // Toggle chevron icon
                this.querySelector('i').classList.toggle('fa-chevron-up');
                this.querySelector('i').classList.toggle('fa-chevron-down');
            });
        });
    </script>
</body>
</html>
