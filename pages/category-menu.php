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
          <input type="text" name="search" class="searchInput" data-type="category" placeholder="Search category...">
        </div>
        <a class="action-btn add-btn" href="/category/save">
          <span class="material-icons-outlined">add</span> Add New Category
        </a>
      </div>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>Category Name</th>
          <th>Date Created</th>
          <th>Date Updated</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="categoryResults">
        <?php if (!empty($categories)): ?>
          <?php foreach ($categories as $category): ?>
            <tr>
              <td><?= htmlspecialchars($category['category_name']) ?></td>
              <td> <?= htmlspecialchars(date('F j, Y g:i A', strtotime($category['datecreate']))) ?> </td>
              <td>
                <?php
                if (!empty($category['dateupdate'])) {
                  echo htmlspecialchars(date('F j, Y g:i A', strtotime($category['dateupdate'])));
                } else {
                  echo "No update yet";
                }
                ?>
              </td>
              <td>
                <a class="edit-btn" href="/category/save?categoryId=<?= $category['categoryId'] ?>">
                  <span class="material-icons-outlined">edit</span>
                </a>
                <a class="delete-btn" onclick="confirmDelete(event, <?php echo $category['categoryId']; ?>, 'category')">
                  <span class="material-icons-outlined">delete</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td style="text-align: center; padding: 20px; font-weight: bold;">
              No category found.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>

    </table>
  </div>

</main>