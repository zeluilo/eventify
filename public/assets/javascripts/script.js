// ==============================
// General Script Initialization
// ==============================
console.log("Script is working!");


// ==============================
// SweetAlert Logout Confirmation
// ==============================
function confirmLogout(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/users/logout';
        }
    });
}

function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete event?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/events/delete';
        }
    });
}

function confirmUser(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/users/delete';
        }
    });
}

function confirmCategory(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete category?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/category/delete';
        }
    });
}


// ==============================
// Set Minimum Date & Time for Event Input
// ==============================
document.addEventListener('DOMContentLoaded', function () {
    const eventDateTimeInput = document.getElementById("event_datetime");
    if (eventDateTimeInput) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        eventDateTimeInput.min = currentDateTime;
    }
});


// ==============================
// Live Event Search (AJAX Filtering)
// ==============================

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const categoryLinks = document.querySelectorAll('.category-link');
    const eventCardsContainer = document.getElementById('eventCards');
    const clearFilterButton = document.getElementById('clearFilter');
    let currentCategory = '';

    console.log("DOM fully loaded. Script running...");

    // Handle search input keyup
    searchInput.addEventListener('keyup', function () {
        const searchTerm = this.value.trim();
        console.log(`Search input: "${searchTerm}" | Current Category: "${currentCategory}"`);
        fetchFilteredEvents(searchTerm, currentCategory);
    });

    // Handle category filter click
    categoryLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            currentCategory = this.getAttribute('data-category');
            const searchTerm = searchInput.value.trim();
            console.log(`Category selected: "${currentCategory}" | Search Term: "${searchTerm}"`);
            fetchFilteredEvents(searchTerm, currentCategory);
        });
    });

    //Handle Clear Filter button click
    clearFilterButton.addEventListener('click', function (e) {
        e.preventDefault();
        currentCategory = '';
        searchInput.value = '';
        fetchFilteredEvents('', currentCategory);
    });

    // AJAX request function
    function fetchFilteredEvents(search = '', category = '') {
        console.log(`Sending AJAX request...`);
        console.log(`Params sent => search: "${search}", category: "${category}"`);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/events/filter', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log(`AJAX response status: ${xhr.status}`);
                if (xhr.status === 200) {
                    console.log('Response received. Updating event list...');
                    eventCardsContainer.innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to load events. Server error or wrong path.');
                }
            }
        };

        const params = `search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`;
        xhr.send(params);
    }
});

function openEditModal(eventId) {
    // Log the eventId to the console for debugging
    console.log("Event ID:", eventId);

    // Make an AJAX request to fetch event details by eventId
    fetch(`/events/fetchEventDetails?eventId=${eventId}`)
        .then(response => response.json()) // Parse the response as JSON
        .then(data => {
            if (data.success) {
                // Populate the modal form with the fetched event data
                document.getElementById('editEventId').value = data.eventId;
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
                document.getElementById('event_date').value = data.event_date;
                document.getElementById('location').value = data.location;

                // Populate the category dropdown dynamically
                const categorySelect = document.getElementById('category_name');
                categorySelect.innerHTML = ''; // Clear existing categories
                data.categories.forEach(function (category) {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (category.id === data.categoryId) {
                        option.selected = true; // Set the current category as selected
                    }
                    categorySelect.appendChild(option);
                });

                // Show the modal
                document.getElementById('editEventModal').style.display = "block";
            } else {
                console.error("Failed to fetch event details:", data.message);
            }
        })
        .catch(error => {
            console.error("Error fetching event details:", error);
        });
}



// Function to close the Edit Event Modal
function closeEditModal() {
    document.getElementById('editEventModal').style.display = "none";
}

// Optional: Close modal when clicking outside of the modal
window.onclick = function (event) {
    if (event.target == document.getElementById('editEventModal')) {
        closeEditModal();
    }
}



// ==============================
// (Optional) Error Modal Handling (If Needed Later)
// ==============================
// function showErrorMessage(message) {
//     document.getElementById('error-message').textContent = message;
//     document.getElementById('error-modal').style.display = 'block';
// }
//
// function closeErrorMessage() {
//     document.getElementById('error-modal').style.display = 'none';
// }
//
// setTimeout(function () {
//     closeErrorMessage();
// }, 5000);
