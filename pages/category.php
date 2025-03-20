<?php
// Check for success or error messages in session and display the appropriate SweetAlert
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

if (isset($_SESSION['categoryUpdateSuccess']) && $_SESSION['categoryUpdateSuccess'] === true) {
    unset($_SESSION['categoryUpdateSuccess']);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'Category Updated Successfully!',
            text: 'The event category has been updated.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>";
}

// Include error messages
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
        <form action="/category/save" method="post" class="php-email-form">
            <h2 class="form-title">Add New Category</h2>

            <!-- Category ID hidden for update purposes -->
            <input type="hidden" name="categoryId" value="<?php echo $category['categoryId'] ?? ''; ?>">

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" placeholder="Enter Event Category" name="category_name" value="<?php echo $category['category_name'] ?? ''; ?>" required>
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit">Save Category</button>
            </div>

        </form>
    </div>
</section>
