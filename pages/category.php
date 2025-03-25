<?php
include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2><?php echo isset($category) ? 'Edit Event Category' : 'Add Event Category'; ?></h2>
        <p><?php echo isset($category) ? 'Modify the category details below.' : 'Create a new category for classifying events.'; ?></p>
        <a href="/users/home#categories" class="btn-get-started">View Categories</a>
    </div>
</section>

<section id="about" class="about-section">
    <div class="form-container">
        <form action="/category/save" method="post" class="php-email-form">
            <h2 class="form-title">Add New Category</h2>
            <input type="hidden" name="categoryId" value="<?php echo isset($category) ? $category[0]['categoryId'] : null ?>" />

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" placeholder="Enter Event Category" 
                name="category_name" value="<?php echo isset($category) ? htmlspecialchars($category[0]['category_name']) : ''; ?>" required>
            </div>

            <div class="form-group submit-group">
                <button type="submit" name="submit">Save Category</button>
            </div>

        </form>
    </div>
</section>
