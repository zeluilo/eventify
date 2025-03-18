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