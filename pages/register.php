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
            <h2 class="form-title"><?php echo isset($user) ? 'Edit Your Account' : 'Create an Account'; ?></h2>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($user) ? htmlspecialchars($user['first_name']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo isset($user) ? htmlspecialchars($user['last_name']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>" required>
            </div>

            <!-- Password Field -->
            <?php
            // Check if 'userDetails' is set in the session before accessing it
            if (isset($_SESSION['userDetails']) && isset($user) && $user['userId'] === $_SESSION['userDetails']['userId']) : ?>
                <!-- For the logged-in user (who is not an admin), the password fields are enabled -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="" required>
                </div>

                <div class="form-group">
                    <label for="repeat_password">Repeat Password</label>
                    <input type="password" id="repeat_password" name="repeat_password" value="" required>
                </div>
                <input type="checkbox" class="form-group" id="showPassword" onclick="togglePasswordVisibility()"> Show Password
            <?php elseif (!isset($_SESSION['userDetails'])) : ?>
                <!-- If session is not set, show editable password fields -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="" required>
                </div>

                <div class="form-group">
                    <label for="repeat_password">Repeat Password</label>
                    <input type="password" id="repeat_password" name="repeat_password" value="" required>
                </div>
                <input type="checkbox" class="form-group" id="showPassword" onclick="togglePasswordVisibility()"> Show Password
            <?php else : ?>
                <!-- If the user is an admin, disable the password fields -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="" required disabled>
                </div>

                <div class="form-group">
                    <label for="repeat_password">Repeat Password</label>
                    <input type="password" id="repeat_password" name="repeat_password" value="" required disabled>
                </div>
                <input type="checkbox" class="form-group" id="showPassword" onclick="togglePasswordVisibility()"> Show Password
            <?php endif; ?>


            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo isset($user) ? htmlspecialchars($user['phone']) : ''; ?>" required>
            </div>

            <!-- Display image upload only when editing -->
            <?php if (isset($user)): ?>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                    <?php if (!empty($user['profile_pic'])): ?>
                        <p>Current Profile Picture: <?php echo $user['profile_pic'] ?></p>

                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group submit-group">
                <button type="submit" name="submit"><?php echo isset($user) ? 'Update Account' : 'Register'; ?></button>
            </div>
            <p class="form-link">Already have an account? <a href="/users/login">Login here</a></p>

            <?php if (isset($user)): ?>
                <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['userId']); ?>">
            <?php endif; ?>

        </form>
    </div>
</section>