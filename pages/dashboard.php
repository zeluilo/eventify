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
  let pageVariable = " "; // Set the default page to category

  // This function will set the page type based on the selected menu
  function showMenu(type) {
    console.log('Selected menu:', type); // Debugging line to check selected menu

    // Set the pageVariable based on the selected menu
    pageVariable = type;
    console.log('Page type set to:', pageVariable); // Debugging line to verify the page type

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
      console.log('Showing menu:', type);
    } else {
      console.error('Menu not found:', type + "-menu"); // Debugging line to catch any errors
    }
  }

  function searchCategories() {
    var searchTerm = document.getElementById('searchAdmin').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/events/search', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('categoryResults').innerHTML = xhr.responseText;
        }
    };
    xhr.send('search=' + encodeURIComponent(searchTerm));
}

function searchUsers() {
    var searchTerm = document.getElementById('searchAdmin').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/events/search', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('categoryResults').innerHTML = xhr.responseText;
        }
    };
    xhr.send('search=' + encodeURIComponent(searchTerm));
}

function searchEvents() {
    var searchTerm = document.getElementById('searchAdmin').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/events/search', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('categoryResults').innerHTML = xhr.responseText;
        }
    };
    xhr.send('search=' + encodeURIComponent(searchTerm));
}

</script>