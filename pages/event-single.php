<?php
if (isset($_SESSION['categoryCreationSuccess']) && $_SESSION['categoryCreationSuccess'] === true) {
    unset($_SESSION['categoryCreationSuccess']);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({
            title: 'Category Created Successfully!',
            text: 'Your new event category has been added.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
      </script>";
}

include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background" style="background-image: url('<?php echo '../images/events/' . $event['image']; ?>');">
<div class="home-content">
        <h2><?php echo $event['title']; ?></h2>
        <!-- <p>Create a new category for classifying events.</p> -->
        <!-- <a href="/users/home#categories" class="btn-get-started">View Categories</a> -->
    </div>
</section>

<section class="event-section">
    <div class="event-details-container">

        <h2 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h2>

        <div class="event-image-wrapper">
            <img src="<?php echo '../images/events/' . $event['image']; ?>" alt="Event Image" class="event-image">
        </div>

        <div class="event-info">
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            <p><strong>Date & Time:</strong> <?php echo date('F j, Y g:i A', strtotime($event['event_date'])); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($event['category_name']); ?></p>
            <p><strong>Posted By:</strong> <?php echo htmlspecialchars($event['first_name'] . ' ' . $event['last_name']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($event['email']); ?> | <?php echo htmlspecialchars($event['phone']); ?></p>
            <p><strong>Event Created On:</strong> <?php echo date('F j, Y', strtotime($event['event_created'])); ?></p>
            <p><strong>Last Updated:</strong> <?php echo date('F j, Y', strtotime($event['event_updated'])); ?></p>
        </div>

        <div class="event-actions">
            <a href="/events/edit?eventId=<?php echo $event['eventId']; ?>" class="btn btn-edit">Edit Event</a>
            <a href="/events/delete?eventId=<?php echo $event['eventId']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">Delete Event</a>
        </div>

    </div>
</section>
