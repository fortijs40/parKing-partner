// Function to toggle the notification dropdown
function toggleNotificationDropdown() {
    const notificationDropdown = document.getElementById("notification-dropdown");
    notificationDropdown.classList.toggle("show");
}

// Event listener to handle click on the bell icon
const notificationIcon = document.getElementById("notification-icon");
notificationIcon.addEventListener("click", toggleNotificationDropdown);

// Function to populate the notification dropdown with data (replace with your own data retrieval logic)
function populateNotification() {
    // Replace the following with your data retrieval logic from the database
    const notificationData = [
        { message: "Parking Space 1 is full", timestamp: "2 hours ago" },
        { message: "New reservation: Parking Space 2", timestamp: "1 day ago" },
        // Add more notification items as needed
    ];

    const notificationDropdown = document.getElementById("notification-dropdown");

    // Clear existing content
    notificationDropdown.innerHTML = "";

    // Create and append notification items
    notificationData.forEach((item) => {
        const notificationItem = document.createElement("div");
        notificationItem.classList.add("notification-item");

        const message = document.createElement("p");
        message.textContent = item.message;
        notificationItem.appendChild(message);

        const timestamp = document.createElement("span");
        timestamp.textContent = item.timestamp;
        notificationItem.appendChild(timestamp);

        notificationDropdown.appendChild(notificationItem);
    });
}

// Call the populateNotification function to load initial notification content
populateNotification();
