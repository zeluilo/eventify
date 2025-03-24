<main id="account-menu" class="main-container" style="display: none;">
  <!-- <div class="main-title">
        <h2>Events</h2>
    </div> -->

  <div class="data-section">
    <div class="table-header">
      <h2>Staff List</h2>
      <div class="table-header-right">
        <div class="search-bar">
          <input type="text" name="search" id="searchInput" placeholder="Search staff...">
        </div>
        <a class="action-btn add-btn" href="/events/save">
          <span class="material-icons-outlined">add</span> Add New Staff
        </a>
      </div>
    </div>


    <table class="data-table">
      <thead>
        <tr>
          <th>Full name</th>
          <th>Email</th>
          <th>Phone number</th>
          <th>Staff Role</th>
          <th>Created at</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $event): ?>
          <tr>
            <td> <?= htmlspecialchars($event['first_name'] . " " . $event['last_name']) ?> </td>
            <td> <?= htmlspecialchars(string: $event['email']) ?> </td>
            <td> <?= htmlspecialchars(string: $event['phone']) ?> </td>
            <td> <?= htmlspecialchars($event['role']) ?> </td>
            <td> <?= htmlspecialchars($event['user_created']) ?> </td>

            <td>
              <button class="edit-btn"><span class="material-icons-outlined">edit</span></button>
              <button class="delete-btn"><span class="material-icons-outlined">delete</span></button>
            </td>
          </tr>
        <?php endforeach; ?>
        <!-- More rows dynamically -->
      </tbody>
    </table>
  </div>
</main>