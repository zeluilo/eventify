<main id="category-menu" class="main-container" style="display: block;">
  <!-- <div class="main-title">
    <h2>Categories</h2>
  </div> -->

  <!-- <div class="main-cards">

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

  </div> -->

  <div class="data-section">
    <div class="table-header">
        <h2>Category List</h2>
        <div class="table-header-right">
            <div class="search-bar">
                <input type="text" name="search" id="searchAdmin" placeholder="Search category..." oninput="searchCategories()">
            </div>
            <a class="action-btn add-btn" href="/events/save">
                <span class="material-icons-outlined">add</span> Add New Category
            </a>
        </div>
    </div>

    <table  class="data-table">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoryResults">
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3">No category found.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category['category_name']) ?></td>
                    <td><?= htmlspecialchars($category['datecreate']) ?></td>
                    <td>
                        <button class="edit-btn"><span class="material-icons-outlined">edit</span></button>
                        <button class="delete-btn"><span class="material-icons-outlined">delete</span></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</main>