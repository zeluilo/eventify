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

function confirmDelete(event, itemId, itemType) {
    event.preventDefault();
    console.log(itemType + " ID:", itemId); // Log the item ID to check if it's correct

    let confirmationMessage = `Do you really want to delete the ${itemType}?`;
    // Check itemType and create the appropriate URL for deletion
    let deleteUrl = '';
    if (itemType === 'event') {
        deleteUrl = `/events/delete?eventId=${itemId}`;
    } else if (itemType === 'user') {
        deleteUrl = `/users/delete?userId=${itemId}`;
    } else {
        deleteUrl = `/${itemType}/delete?${itemType}Id=${itemId}`;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: confirmationMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, Delete ${itemType}`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the delete URL with the item ID
            window.location.href = deleteUrl;
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

// Function to format the event date and time
function formatDate(eventDate) {
    const date = new Date(eventDate);
    return date.toLocaleString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    });
}


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

document.addEventListener("DOMContentLoaded", function () {
    const navSections = document.querySelectorAll('.nav-section');

    navSections.forEach(section => {
        section.querySelector('h3').addEventListener('click', () => {
            section.classList.toggle('active');

            console.log(`Toggled submenu for: ${section.querySelector('h3').innerText}`);
        });
    });
});