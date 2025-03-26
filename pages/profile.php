<?php
// Check if the user is logged in or registered
$isLoggedInOrRegistered = isset($_SESSION['userDetails']);

// Check if the logged-in user's checkAdmin is "USER"
$isUser = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'USER';
$isAdmin = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'ADMIN';
include '../includes/error-message.php';
?>


<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2>Edit Profile Details</h2>
        <p>Update your personal information and preferences.</p>
    </div>
</section>

<section class="event-section">
    <div class="event-details-container">

        <div class="event-image-wrapper">
            <img src="<?php echo !empty($user['profile_pic']) ? '../images/profile_pics/' . $user['profile_pic'] : '../assets/images/profile.png'; ?>"
             alt="User Profile Image" class="event-image">
        </div>


        <div class="event-info">
            <h2>Your Profile Information</h2>

            <table class="event-details-table">
                <tr>
                    <td><strong>Full name:</strong></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                </tr>
                <tr>
                    <td><strong>Role:</strong></td>
                    <td><?php echo htmlspecialchars($user['user_role']); ?></td>
                </tr>
                <?php if ($isAdmin) : ?>
                    <tr>
                        <td><strong>User Created On:</strong></td>
                        <td><?php echo date('F j, Y g:i A', strtotime($user['datecreated'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td>
                            <?php
                            if (!empty($user['dateupdated'])) {
                                echo date('F j, Y g:i A', strtotime($user['dateupdated']));
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
                    <a href="/users/save?userId=<?php echo $user['userId']; ?>" class="btn btn-edit">Edit Account</a>
                    <a href="#" class="btn btn-delete"
                        onclick="confirmDelete(event, <?php echo $user['userId']; ?>, 'user')">
                        Delete Account
                    </a>
                </div>
            <? endif; ?>

        </div>

    </div>
</section>