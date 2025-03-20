<?php
// Show success message after event creation
if (isset($_SESSION['eventCreationSuccess']) && $_SESSION['eventCreationSuccess'] === true) {
    unset($_SESSION['eventCreationSuccess']);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({
            title: 'Event Created Successfully!',
            text: 'Your event has been saved and can now be viewed by others.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
      </script>";
}

// Include error messages
include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background" style="background-image: url('<?php echo isset($event) && !empty($event[0]['image']) ? '../images/events/' . htmlspecialchars($event[0]['image']) : ''; ?>');">
    <div class="home-content">
        <h2><?php echo isset($event) ? 'Edit Event' : 'Create Event'; ?></h2>
        <p><?php echo isset($event) ? 'Modify the event details below.' : 'Use the form below to create and publish an event.'; ?></p>
        <a href="/events/view" class="btn-get-started">View Events</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/events/<?php echo isset($event) ? 'save?=' . $event[0]['eventId'] : 'save'; ?>" method="post" enctype="multipart/form-data" class="php-email-form">
            <h2 class="form-title"><?php echo isset($event) ? 'Edit Event' : 'Create New Event'; ?></h2>
            <input type="hidden" name="eventId" value="<?php echo isset($event) ? $event[0]['eventId'] : null ?>" />
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" value="<?php echo isset($event) ? htmlspecialchars($event[0]['title']) : ''; ?>" placeholder="Enter Title of Event" required>
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="5" placeholder="Enter Description of Event" required><?php echo isset($event) ? htmlspecialchars($event[0]['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="event_datetime">Event Date & Time</label>
                <input type="datetime-local" id="event_datetime" name="event_date" value="<?php echo isset($event) ? date('Y-m-d\TH:i', strtotime($event[0]['event_date'])) : ''; ?>" required min="<?= $currentDateTime ?>">
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="<?php echo isset($event) ? htmlspecialchars($event[0]['location']) : ''; ?>" placeholder="Enter Location of Event" required>
            </div>

            <div class="form-group">
                <label for="categoryId">Category</label>
                <select id="categoryId" name="categoryId" required>
                    <option value="" disabled selected>-- Select a category --</option>
                    <?php
                    foreach ($category as $row) {
                        echo '<option value="' . $row['categoryId'] . '"' . (isset($event) && $event[0]['categoryId'] == $row['categoryId'] ? ' selected' : '') . '>' . htmlspecialchars($row['category_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Event Image</label>
                <?php if (isset($event) && !empty($event[0]['image'])): ?>
                    <p>Current Image: <?php echo $event[0]['image']?></p>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit"><?php echo isset($event) ? 'Update Event' : 'Create Event'; ?></button>
            </div>
        </form>
    </div>
</section>


<!-- Event Management Table -->
<!-- <section id="event-management" class="event-management-section">
    <h2>Manage Events</h2>

    <?php if (empty($events)): ?>
        <p>No current events</p>
    <?php else: ?>
        <table class="events-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date & Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($events as $event) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($event[0]['title']) . '</td>';
                    echo '<td>' . date('Y-m-d H:i', strtotime($event[0]['event_date'])) . '</td>';
                    echo '<td>' . htmlspecialchars($event[0]['location']) . '</td>';
                    echo '<td>';
                    echo '<a href="/events/view/' . $event[0]['eventId'] . '" class="btn-view">View</a>';
                    echo '<a href="/events/delete/' . $event[0]['eventId'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this event?\')">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</section> -->