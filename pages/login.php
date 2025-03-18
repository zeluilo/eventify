<section id="home" class="home section dark-background">

    <!-- <img src="assets/img/hero-bg.jpg" alt="Hero Background"> -->

    <div class="home-content">
        <h2>Login</h2>
        <p>Log into your account.</p>
        <a href="#events" class="btn-get-started">Browse Events</a>
    </div>

</section>

<div class="form-container">
  <form action="/users/login" method="post" class="php-email-form">
    <h2 class="form-title">Login</h2>

    <div class="form-group">
      <label for="login_email">Email Address</label>
      <input type="email" id="login_email" name="email" required>
    </div>

    <div class="form-group">
      <label for="login_password">Password</label>
      <input type="password" id="login_password" name="password" required>
    </div>

    <div class="form-group submit-group">
      <button type="submit" name="submit">Login</button>
    </div>

    <p class="form-link">Don’t have an account? <a href="/users/register">Register here</a></p>
  </form>
</div>
