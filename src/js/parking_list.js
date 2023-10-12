// Sample JSON data for demonstration
let parkingData = [
    { street: "Street 1", isFree: true, freeSpaces: 10 },
    { street: "Street 2", isFree: false, freeSpaces: 0 },
    // Add more data as needed
];

// Function to filter and display parking data
function displayParkingList(isFreeFilter) {
    const parkingListContainer = document.querySelector('.parking-list');
    parkingListContainer.innerHTML = ''; // Clear the existing list

    parkingData.forEach(parkingSpace => {
        if (isFreeFilter === 'all' || parkingSpace.isFree.toString() === isFreeFilter) {
            const parkingSpaceElement = document.createElement('div');
            parkingSpaceElement.className = 'parking-space';

            const nameElement = document.createElement('h2');
            nameElement.textContent = parkingSpace.street;

            const isFreeElement = document.createElement('p');
            isFreeElement.textContent = parkingSpace.isFree ? 'Available' : 'Not Available';

            const freeSpacesElement = document.createElement('p');
            freeSpacesElement.textContent = `Free Spaces: ${parkingSpace.freeSpaces}`;

            parkingSpaceElement.appendChild(nameElement);
            parkingSpaceElement.appendChild(isFreeElement);
            parkingSpaceElement.appendChild(freeSpacesElement);
            parkingListContainer.appendChild(parkingSpaceElement);
        }
    });
}

// Function to handle filter change
function handleFilterChange() {
    const isFreeFilter = document.getElementById('filterIsFree').value;
    displayParkingList(isFreeFilter);
}

// Function to update the parking data with webhook data
function updateParkingDataFromWebhook(webhookData) {
    // Process the webhook data and update the parkingData array
    parkingData = webhookData.parkingData; // Replace 'parkingData' with the actual data key in your webhook

    // Update the displayed parking data
    displayParkingList('all'); // To refresh the data based on the 'all' filter
}

// Function to fetch parking data from the local database
function fetchParkingDataFromDatabase() {
    fetch('/api/getParkingData') // Replace with your server-side endpoint
        .then(response => response.json())
        .then(data => {
            // Update the parking data in JavaScript
            parkingData = data;
            displayParkingList('all'); // Refresh the data
        })
        .catch(error => console.error(error));
}

// Call the function to display the initial data
displayParkingList('all');

// Attach filter change event listener
document.getElementById('filterIsFree').addEventListener('change', handleFilterChange);

// Simulate updating parking data from the webhook (for demonstration)
updateParkingDataFromWebhook(webhookData);

// Fetch parking data from the local database when the page loads
fetchParkingDataFromDatabase();