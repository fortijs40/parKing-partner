// Sample JSON data for demonstration
// const localDatabaseData = [
//     { name: "Parking Space 1", isFull: false },
//     { name: "Parking Space 2", isFull: true },
    // Add more data as needed
// ];

// Function to fetch and display API data
// async function fetchApiData() {
//     try {
//         const response = await fetch('API_URL_HERE'); // Replace with your API URL
//         if (!response.ok) {
//             throw new Error('Network response was not ok.');
//         }
//         const apiData = await response.json();
//         displayParkingList(apiData);
//     } catch (error) {
//         console.error('Error fetching API data:', error);
//     }
// }

// Function to display parking list data
// function displayParkingList(data) {
//     const parkingListContainer = document.querySelector('.parking-list');

//     data.forEach(parkingSpace => {
//         const parkingSpaceElement = document.createElement('div');
//         parkingSpaceElement.className = 'parking-space';

//         const nameElement = document.createElement('h2');
//         nameElement.textContent = parkingSpace.name;

//         const statusElement = document.createElement('p');
//         statusElement.textContent = parkingSpace.isFull ? 'It is full' : 'It is not full';

//         parkingSpaceElement.appendChild(nameElement);
//         parkingSpaceElement.appendChild(statusElement);
//         parkingListContainer.appendChild(parkingSpaceElement);
//     });
// }

// Call the function to fetch API data and display it
// fetchApiData();

// Function to run when the page loads
// function onLoad() {
//     // Load data from the local database and display it
//     displayParkingList(localDatabaseData);
// }

// Attach onLoad function to the window.onload event
// if (window.addEventListener) {
//     window.addEventListener('load', onLoad, false);
// } else if (window.attachEvent) {
//     window.attachEvent('onload', onLoad);
// } else {
//     window.onload = onLoad;
// }


// Sample JSON data for demonstration
const parkingData = [
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

// Call the function to display the initial data
displayParkingList('all');

// Attach filter change event listener
document.getElementById('filterIsFree').addEventListener('change', handleFilterChange);
