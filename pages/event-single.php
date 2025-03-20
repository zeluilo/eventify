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

// Check if the user is logged in or registered
$isLoggedInOrRegistered = isset($_SESSION['userDetails']);

// Check if the logged-in user's checkAdmin is "USER"
$isUser = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'USER';
$isAdmin = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'ADMIN';
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

        <div class="event-image-wrapper">
            <img src="<?php echo '../images/events/' . $event['image']; ?>" alt="Event Image" class="event-image">
        </div>

        <div class="event-info">
            <h2 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h2>

            <table class="event-details-table">
                <tr>
                    <td><strong>Description:</strong></td>
                    <td><?php echo nl2br(htmlspecialchars($event['description'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td><?php echo date('F j, Y g:i A', strtotime($event['event_date'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Location:</strong></td>
                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                </tr>
                <tr>
                    <td><strong>Category:</strong></td>
                    <td><?php echo htmlspecialchars($event['category_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Posted By:</strong></td>
                    <td><?php echo htmlspecialchars($event['first_name'] . ' ' . $event['last_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Contact:</strong></td>
                    <td><?php echo htmlspecialchars($event['email']); ?> | <?php echo htmlspecialchars($event['phone']); ?></td>
                </tr>
                <?php if ($isAdmin) : ?>
                    <tr>
                        <td><strong>Event Created On:</strong></td>
                        <td><?php echo date('F j, Y g:i A', strtotime($event['event_created'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td>
                            <?php
                            if (!empty($event['event_updated'])) {
                                echo date('F j, Y g:i A', strtotime($event['event_updated']));
                            } else {
                                echo "Not updated yet";
                            }
                            ?>
                        </td>
                    </tr>
                <? endif; ?>
            </table>
            <?php if ($isAdmin || $isUser) : ?>

                <div class="event-actions">
                    <a href="/events/save?eventId=<?php echo $event['eventId']; ?>" class="btn btn-edit">Edit Event</a>
                    <a href="#" class="btn btn-delete"
                        onclick="confirmDelete(event, <?php echo $event['eventId']; ?>)">
                        Delete Event
                    </a>


                </div>
            <? endif; ?>

        </div>

    </div>
</section>