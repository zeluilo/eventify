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

  <!-- PHP Includes for Menu Sections -->
  <?php include 'category-menu.php'; ?>  
  <?php include 'admin/event-menu.php'; ?>  
  <?php include 'account-menu.php'; ?>  
</div>

<script>
  // Show the selected menu based on the provided type
  function showMenu(type) {
    console.log('Selected menu:', type); // Debugging line to check selected menu

    // Hide all menus
    const menus = ['category', 'event', 'account'];
    menus.forEach(menu => {
      const menuElement = document.getElementById(menu + "-menu");
      if (menuElement) {
        menuElement.style.display = "none";
        console.log('Hiding menu:', menu); // Debugging line to verify hiding
      }
    });

    // Display the selected menu
    const selectedMenu = document.getElementById(type + "-menu");
    if (selectedMenu) {
      selectedMenu.style.display = "block";
      console.log('Showing menu:', type); // Debugging line to verify showing
    } else {
      console.error('Menu not found:', type + "-menu"); // Debugging line to catch any errors
    }
  }

  document.getElementById("searchAdmin").addEventListener("input", function() {
    let searchQuery = this.value.toLowerCase();

    // Determine the current page and the corresponding table
    let table;
    if (window.location.pathname.includes("category")) {
        table = document.getElementById("categoryTable");
    } else if (window.location.pathname.includes("event")) {
        table = document.getElementById("eventTable");
    } else if (window.location.pathname.includes("user")) {
        table = document.getElementById("userTable");
    }

    if (table) {
        let rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchQuery)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
});

</script>
