function showErrorMessage(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('error-modal').style.display = 'block';
}

function closeErrorMessage() {
    document.getElementById('error-modal').style.display = 'none';
}

setTimeout(function() {
    closeErrorMessage();
}, 5000);
