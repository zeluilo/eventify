<?php
if (isset($_SESSION['categoryCreationSuccess']) && $_SESSION['categoryCreationSuccess'] === true) {
    unset($_SESSION['categoryCreationSuccess']);
    echo "
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

<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2>Add Event Category</h2>
        <p>Create a new category for classifying events.</p>
        <a href="/users/home#categories" class="btn-get-started">View Categories</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/categories/create" method="post" class="php-email-form">
            <h2 class="form-title">Add New Category</h2>

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>

            <div class="form-group">
                <label for="category_description">Category Description</label>
                <textarea id="category_description" name="category_description" rows="4" required></textarea>
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit">Add Category</button>
            </div>

        </form>
    </div>
</section>
