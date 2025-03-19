<?php
if (isset($_SESSION['eventCreationSuccess']) && $_SESSION['eventCreationSuccess'] === true) {
    unset($_SESSION['eventCreationSuccess']);
    echo "
      <script>
        Swal.fire({
            title: 'Event Created Successfully!',
            text: 'Your event has been saved and can now be viewed by others.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
      </script>";
}

include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2>Create Event</h2>
        <p>Use the form below to create and publish an event.</p>
        <a href="/users/home#events" class="btn-get-started">View Events</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/events/create" method="post" enctype="multipart/form-data" class="php-email-form">
            <h2 class="form-title">Create New Event</h2>

            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>

            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" id="event_time" name="event_time" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="categoryId">Category</label>
                <select id="categoryId" name="categoryId" required>
                    <option value="">-- Select Category --</option>
                    <!-- Replace the options below dynamically from DB if needed -->
                    <option value="1">Conference</option>
                    <option value="2">Workshop</option>
                    <option value="3">Seminar</option>
                    <option value="4">Concert</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Event Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit">Create Event</button>
            </div>

        </form>
    </div>
</section>
