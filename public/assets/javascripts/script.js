console.log("Script is working!");

// function showErrorMessage(message) {
//     document.getElementById('error-message').textContent = message;
//     document.getElementById('error-modal').style.display = 'block';
// }

// function closeErrorMessage() {
//     document.getElementById('error-modal').style.display = 'none';
// }

// setTimeout(function () {
//     closeErrorMessage();
// }, 5000);

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

// Event Date & Time input to get current date and time
const now = new Date();
const year = now.getFullYear();
const month = String(now.getMonth() + 1).padStart(2, '0');
const day = String(now.getDate()).padStart(2, '0');
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');

const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

document.getElementById("event_datetime").min = currentDateTime;