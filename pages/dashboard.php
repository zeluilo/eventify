<div class="grid-container">
  <aside id="sidebar">
    <div class="sidebar-title">
      <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
    </div>

    <ul class="sidebar-list">
      <li class="sidebar-list-item">
        <a href="javascript:void(0);" onclick="showMenu('category')">
          <span class="material-icons-outlined">dashboard</span> Categories
        </a>
      </li>
      <li class="sidebar-list-item">
        <a href="javascript:void(0);" onclick="showMenu('event')">
          <span class="material-icons-outlined">event</span> Events
        </a>
      </li>
      <li class="sidebar-list-item">
        <a href="javascript:void(0);" onclick="showMenu('account')">
          <span class="material-icons-outlined">manage_accounts</span> Accounts
        </a>
      </li>
    </ul>
  </aside>

  <?php include 'category-menu.php'; ?>  
  <?php include 'event-menu.php'; ?>  
  <?php include 'account-menu.php'; ?>  
</div>

<script>
  // Show the selected menu based on the provided type
  function showMenu(type) {
    // Hide all menus
    const menus = ['category', 'event', 'account'];
    menus.forEach(menu => {
      document.getElementById(menu + "-menu").style.display = "none";
    });

    // Display the selected menu
    document.getElementById(type + "-menu").style.display = "block";
  }
</script>
