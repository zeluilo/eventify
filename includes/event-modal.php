<!-- Edit Event Modal -->
<div id="editEventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h2>Edit Event</h2>
        <form id="editEventForm" method="POST" action="/events/update">
            <input type="hidden" name="eventId" id="editEventId">
            
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="event_date">Event Date & Time</label>
                <input type="datetime-local" id="event_date" name="event_date" required>
            </div>
            
            <div class="form-group">
                <label for="location">Event Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            
            <div class="form-group">
                <label for="category_name">Event Category</label>
                <select id="category_name" name="category_name" required>
                    <!-- Categories will be populated dynamically -->
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<script>
// JavaScript to handle the modal
function openEditModal(eventId) {
    fetch(`/events/fetchEventDetails?eventId=${eventId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the modal with event data
                document.getElementById('editEventId').value = data.eventId;
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
                document.getElementById('event_date').value = data.event_date;
                document.getElementById('location').value = data.location;

                // Populate the category dropdown dynamically
                const categorySelect = document.getElementById('category_name');
                categorySelect.innerHTML = ''; // Clear existing categories
                const categoryOption = document.createElement('option');
                categoryOption.value = data.categoryId;
                categoryOption.textContent = data.categoryId;  // Assuming category name is returned
                categorySelect.appendChild(categoryOption);

                // Show the modal
                document.getElementById('editEventModal').style.display = "block";  // Make sure the modal is visible
            } else {
                alert(data.message); // Show error message if event is not found
            }
        })
        .catch(error => console.error('Error fetching event details:', error));
}

// Close the modal when the close button is clicked
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('editEventModal').style.display = "none";  // Hide the modal
});
</script>