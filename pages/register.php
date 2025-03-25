<?php include '../includes/error-message.php'; ?>

<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2><?php echo isset($user) ? 'Edit' : 'Register'; ?> Account</h2>
        <p><?php echo isset($user) ? 'Update your account details.' : 'Register a new account.'; ?></p>
        <a href="#events" class="btn-get-started">Browse Events</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/users/save" method="post" class="php-email-form" enctype="multipart/form-data">
            <h2 class="form-title"><?php echo $user ? 'Edit Your Account' : 'Create an Account'; ?></h2>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="repeat_password">Repeat Password</label>
                <input type="password" id="repeat_password" name="repeat_password" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
            </div>

            <!-- Display image upload only when editing -->
            <?php if ($user): ?>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                    <?php if (!empty($user['profile_pic'])): ?>
                        <p>Current Profile Picture:</p>
                        <img src="images/profile_pics/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="100">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group submit-group">
                <button type="submit" name="submit"><?php echo $user ? 'Update Account' : 'Register'; ?></button>
            </div>
            <p class="form-link">Already have an account? <a href="/users/login">Login here</a></p>

            <?php if ($user): ?>
                <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">
            <?php endif; ?>

        </form>
    </div>
</section>
