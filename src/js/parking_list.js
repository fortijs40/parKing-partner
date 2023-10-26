
// Establish a connection to the SSE endpoint
const eventSource = new EventSource('./php/sse_updates.php');
/*
// Event listener for SSE updates
eventSource.onmessage = (event) => {
const notification = JSON.parse(event.data);

if (notification.type === 'reservation') {
console.log(notification.message);
} else if (notification.type === 'report') {
    console.log(notification.message);
} else if (notification.type === 'review') {
    console.log(notification.message);
}
// You can update the notification dropdown or other parts of your page here
};
// Event listener for SSE errors
eventSource.onerror = (error) => {
console.error('SSE error:', error);
};*/
// Get references to elements
function openNotifications() {
document.getElementById("notification-modal").style.display = "block";
}
function closeNotifications(){
document.getElementById("notification-modal").style.display = "none";
}

function openForm() {
document.getElementById("register-parking").style.display = "block";
}
function closeForm() {
document.getElementById("register-parking").style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
let modal = document.getElementById('register-parking');
let notificationModal = document.getElementById('notification-modal');
if (event.target == modal) {
    closeForm();
}
if(event.target == notificationModal){
    closeNotifications();
}
}
// JavaScript functions for handling edit and view more buttons
function editParkingSpot(spotId) {
    // Implement your edit logic here or redirect to an edit page with the spotId
}

function viewMore(spotId) {
    // Implement your view more logic here or redirect to a details page with the spotId
}

function validateForm() {
    // Get the values of the start and end time input fields
    var startTimeInput = document.querySelector('input[name="start_time"]');
    var endTimeInput = document.querySelector('input[name="end_time"]');
    
    var startTime = startTimeInput.value;
    var endTime = endTimeInput.value;

    // Convert the time strings to JavaScript Date objects
    var startTimeDate = new Date("1970-01-01T" + startTime + ":00Z");
    var endTimeDate = new Date("1970-01-01T" + endTime + ":00Z");

    // Check if the end time is earlier than the start time
    if (endTimeDate <= startTimeDate) {
        // Display an error message and prevent form submission
        alert("End time cannot be earlier than or same as start time.");
        endTimeInput.value = "00:00"; // Reset the end time
        return false;
    }

    // If validation passes, allow the form submission
    return true;
}