// JavaScript code for notifications
document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.querySelector('.notification-button');
    const notificationContent = document.querySelector('.notification-content');
    const themeSwitcher = document.getElementById('style-toggle.js');

    // Function to fetch and display notifications (replace with your logic)
    function fetchNotifications() {
        // Simulate fetching notifications from your server or database
        const notifications = [
            'New message from John',
            'You have a meeting at 2 PM',
            // Add more notifications as needed
        ];

        // Display notifications in the dropdown
        notificationContent.innerHTML = '';
        notifications.forEach((notification) => {
            const notificationItem = document.createElement('div');
            notificationItem.classList.add('notification-item');
            notificationItem.textContent = notification;
            notificationContent.appendChild(notificationItem);
        });

        // Show the notification dropdown
        notificationContent.style.display = 'block';
    }

    // Event listener for showing notifications
    notificationButton.addEventListener('click', fetchNotifications);

    // Toggle dark mode class when changing styles
    themeSwitcher.addEventListener('change', () => {
        notificationButton.classList.toggle('dark');
    });

    // Close the dropdown when clicking outside
    window.addEventListener('click', (event) => {
        if (!event.target.matches('.notification-button')) {
            notificationContent.style.display = 'none';
        }
    });
});
