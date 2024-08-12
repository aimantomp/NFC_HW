function toggleDetails(button) {
    const contact = button.closest('.contact');
    const expanded = contact.querySelector('.contact-expanded');
    expanded.style.display = expanded.style.display === 'none' ? 'block' : 'none';
}

function confirmAction(action) {
    const confirmation = confirm(`Are you sure you want to ${action} this contact?`);
    if (confirmation) {
        alert(`${action.charAt(0).toUpperCase() + action.slice(1)}d successfully!`);
    }
}
