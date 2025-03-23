<main id="category-menu" class="main-container" style="display: block;">
  <div class="main-title">
    <h2>Categories</h2>
  </div>

  <div class="main-cards">

    <div class="card">
      <div class="card-inner">
        <h2>LIKES</h2>
        <span class="material-icons-outlined">thumb_up</span>
      </div>
      <h1>4,021</h1>
    </div>

    <div class="card">
      <div class="card-inner">
        <h2>SUBSCRIBERS</h2>
        <span class="material-icons-outlined">subscriptions</span>
      </div>
      <h1>8,731</h1>
    </div>

    <div class="card">
      <div class="card-inner">
        <h2>FOLLOWERS</h2>
        <span class="material-icons-outlined">groups</span>
      </div>
      <h1>3,841</h1>
    </div>

    <div class="card">
      <div class="card-inner">
        <h2>MESSAGES</h2>
        <span class="material-icons-outlined">forum</span>
      </div>
      <h1>1,962</h1>
    </div>

  </div>

  <div class="data-section">
    <div class="table-header">
      <h2>Category List</h2>
      <button class="action-btn add-btn">
        <span class="material-icons-outlined">add</span> Add New Category
      </button>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Category Name</th>
          <th>Date Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sample Data Row (Replace with dynamic PHP later) -->
        <tr>
          <td>1</td>
          <td>Music Events</td>
          <td>2025-03-21 11:00 AM</td>
          <td>
            <button class="edit-btn"><span class="material-icons-outlined">edit</span></button>
            <button class="delete-btn"><span class="material-icons-outlined">delete</span></button>
          </td>
        </tr>
        <?php foreach ($categories as $category): ?>
          <tr>
            <td> <?= htmlspecialchars($category['category_name']) ?> </td>
            <td>2025-03-21 11:00 AM</td>
            <td>
              <button class="edit-btn"><span class="material-icons-outlined">edit</span></button>
              <button class="delete-btn"><span class="material-icons-outlined">delete</span></button>
            </td>
          </tr>
          <li>
            <a href="#" class="category-link" data-category="<?= htmlspecialchars($category['categoryId']) ?>">
              <?= htmlspecialchars($category['category_name']) ?>
            </a>
          </li>
          <div class="dropdown-divider"></div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>