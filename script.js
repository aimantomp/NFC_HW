function toggleDetails(button) {
    const contact = button.closest('.contact');
    const details = contact.querySelector('.contact-expanded');
    const isVisible = details.style.display === 'block';

    // Hide all other expanded details
    document.querySelectorAll('.contact-expanded').forEach(el => {
        el.style.display = 'none';
    });

    // Toggle visibility of the clicked contact's details
    details.style.display = isVisible ? 'none' : 'block';
}
