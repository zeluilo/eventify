<?php


include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background">
    <!-- <img src="assets/img/hero-bg.jpg" alt="Hero Background"> -->

    <div class="home-content">
        <h2>Register</h2>
        <p>Register an Account.</p>
        <a href="#events" class="btn-get-started">Browse Events</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/users/register" method="post" class="php-email-form">
            <h2 class="form-title">Create an Account</h2>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password">Repeat Password</label>
                <input type="password" id="repeat_password" name="repeat_password" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit">Register</button>
            </div>
            <p class="form-link">Already have an account? <a href="/users/login">Login here</a></p>

        </form>
    </div>
</section>