<?php
// Show success message after event creation
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
        <h2><?php echo isset($event) ? 'Edit Event' : 'Create Event'; ?></h2>
        <p><?php echo isset($event) ? 'Modify the event details below.' : 'Use the form below to create and publish an event.'; ?></p>
        <a href="/users/home#events" class="btn-get-started">View Events</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/events/<?php echo isset($event) ? 'edit/' . $event['eventId'] : 'create'; ?>" method="post" enctype="multipart/form-data" class="php-email-form">
            <h2 class="form-title"><?php echo isset($event) ? 'Edit Event' : 'Create New Event'; ?></h2>

            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" value="<?php echo isset($event) ? $event['title'] : ''; ?>" placeholder="Enter Title of Event" required>
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="5" placeholder="Enter Description of Event" required><?php echo isset($event) ? $event['description'] : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="event_datetime">Event Date & Time</label>
                <input type="datetime-local" id="event_datetime" name="event_datetime" value="<?php echo isset($event) ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : ''; ?>" required min="<?= $currentDateTime ?>">
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="<?php echo isset($event) ? $event['location'] : ''; ?>" placeholder="Enter Location of Event" required>
            </div>

            <div class="form-group">
                <label for="categoryId">Category</label>
                <select id="categoryId" name="categoryId" required>
                    <?php
                    foreach ($category as $row) {
                        echo '<option value="' . $row['categoryId'] . '"' . (isset($event) && $event['categoryId'] == $row['categoryId'] ? ' selected' : '') . '>' . $row['category_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Event Image</label>
                <?php if (isset($event) && !empty($event['image'])): ?>
                    <p>Current Image: <img src="images/events/<?php echo $event['image']; ?>" alt="Event Image" width="100"></p>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit"><?php echo isset($event) ? 'Update Event' : 'Create Event'; ?></button>
            </div>

        </form>
    </div>
</section>

<section id="event-management" class="event-management-section">
    <h2>Manage Events</h2>
    <table class="events-table">
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Description</th>
                <th>Date & Time</th>
                <th>Location</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events as $event) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($event['title']) . '</td>';
                echo '<td>' . htmlspecialchars($event['description']) . '</td>';
                echo '<td>' . date('Y-m-d H:i', strtotime($event['event_date'])) . '</td>';
                echo '<td>' . htmlspecialchars($event['location']) . '</td>';
                echo '<td>' . htmlspecialchars($event['category_name']) . '</td>';
                echo '<td>';
                echo '<a href="/events/view/' . $event['eventId'] . '" class="btn-view">View</a>';
                echo '<a href="/events/edit/' . $event['eventId'] . '" class="btn-edit">Edit</a>';
                echo '<a href="/events/delete/' . $event['eventId'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this event?\')">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</section>