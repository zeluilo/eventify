<main id="account-menu" class="main-container" style="display: none;">
  <!-- <div class="main-title">
        <h2>users</h2>
    </div> -->

  <div class="data-section">
    <div class="table-header">
      <h2>Staff List</h2>
      <div class="table-header-right">
        <div class="search-bar">
          <input type="text" name="search" class="searchInput" data-type="account" placeholder="Search staff...">
        </div>
        <a class="action-btn add-btn" href="/users/save">
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
      <tbody id="userResults">
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td> <?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?> </td>
              <td> <?= htmlspecialchars($user['email']) ?> </td>
              <td> <?= htmlspecialchars($user['phone']) ?> </td>
              <td> <?= htmlspecialchars($user['user_role']) ?> </td>
              <td> <?= htmlspecialchars(date('F j, Y', strtotime($user['datecreated']))) ?> </td>
              <td>
                <a class="edit-btn" href="/users/view?uuId=<?= $user['uuId'] ?>">
                  <span class="material-icons-outlined">edit</span>
                </a>
                <a class="delete-btn" onclick="confirmDelete(event, <?php echo $user['uuId']; ?>, 'user')">
                  <span class="material-icons-outlined">delete</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td style="text-align: center; padding: 20px; font-weight: bold;">
              No account found.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>

    </table>
  </div>
</main>