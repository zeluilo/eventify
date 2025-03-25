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
  let pageVariable = "category"; // Set the default page to category

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


  document.querySelectorAll('.searchInput').forEach(input => {
    input.addEventListener('keyup', function() {
      var searchTerm = this.value.trim();
      var searchType = this.getAttribute('data-type');

      console.log(`Searching ${searchType}:`, searchTerm);

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/events/search', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);

          // Log the entire response to inspect its structure
          console.log(response);

          // Determine which part of the response to use based on searchType
          var data = [];
          if (searchType === 'category') {
            data = response.categories || []; // Default to empty array if categories are not found
          } else if (searchType === 'event') {
            data = response.events || [];
          } else if (searchType === 'account') {
            data = response.users || [];
          }

          // Ensure the data is an array and contains results
          if (!Array.isArray(data) || data.length === 0) {
            console.log(`No results found for ${searchType}.`);
            return;
          }

          // Determine which table to update based on searchType
          var resultTableId = searchType === 'category' ? 'categoryResults' :
            searchType === 'event' ? 'eventResults' :
            'userResults'; // Default to account users

          updateTable(resultTableId, data);
        }
      };

      xhr.send(`search=${encodeURIComponent(searchTerm || '')}&type=${searchType}`);
    });
  });

  function updateTable(elementId, data) {
    console.log(`Updating table: ${elementId} with data:`, data);

    var tableBody = document.getElementById(elementId);
    if (!tableBody) {
      console.error(`Element with ID ${elementId} not found.`);
      return;
    }

    tableBody.innerHTML = '';

    // Check if data is valid (an array) and has items
    if (!Array.isArray(data) || data.length === 0) {
      console.log(`No results found for ${elementId}.`);
      tableBody.innerHTML = '<tr><td colspan="6">No results found.</td></tr>';
      return;
    }

    // Example for user fields, adjust for categories or events
    const fields = elementId === 'userResults' ? ['userId', 'first_name', 'last_name', 'email', 'phone', 'user_role', 'datecreated'] :
      elementId === 'categoryResults' ? ['category_name', 'datecreate'] : ['event_name', 'event_date'];

    data.forEach(item => {
      var row = '<tr>';

      // For users, let's join 'first_name' and 'last_name' into 'full_name'
      if (elementId === 'userResults') {
        var fullName = item.first_name + ' ' + item.last_name; // Combine first and last name
        row += '<td>' + fullName + '</td>'; // Add full name in place of separate first and last names
        row += '<td>' + item.email + '</td>';
        row += '<td>' + item.phone + '</td>';
        row += '<td>' + item.user_role + '</td>';
        row += '<td>' + item.datecreated + '</td>';
      }

      // For categories, you might want to format the category name and date created
      else if (elementId === 'categoryResults') {
        row += '<td>' + item.category_name + '</td>';
        row += '<td>' + item.datecreate.toLocaleDateString() + '</td>'; // Format date as needed
      }

      // For events, join multiple fields or format data as needed
      else if (elementId === 'eventResults') {
        row += '<td>' + item.event_name + '</td>';
        row += '<td>' + new Date(item.event_date).toLocaleDateString() + '</td>'; // Format date as needed
      }

      row += `
    <td>
      <button class="edit-btn"><span class="material-icons-outlined">edit</span></button>
      <button class="delete-btn"><span class="material-icons-outlined">delete</span></button>
    </td>
  `;

      row += '</tr>';
      tableBody.innerHTML += row;
    });

    console.log(`Table ${elementId} updated successfully.`);

  }
</script>