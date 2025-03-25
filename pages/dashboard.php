<?php
include '../includes/error-message.php';
?>

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
  let pageVariable = "category";

  // This function will set the page type based on the selected menu
  function showMenu(type) {
    // Set the pageVariable based on the selected menu
    pageVariable = type;

    // Hide all menus
    const menus = ['category', 'event', 'account'];
    menus.forEach(menu => {
      const menuElement = document.getElementById(menu + "-menu");
      if (menuElement) {
        menuElement.style.display = "none";
      }
    });

    // Display the selected menu
    const selectedMenu = document.getElementById(type + "-menu");
    if (selectedMenu) {
      selectedMenu.style.display = "block";
    } else {
      console.error('Menu not found:', type + "-menu");
    }
  }


  document.querySelectorAll('.searchInput').forEach(input => {
    input.addEventListener('keyup', function() {
      var searchTerm = this.value.trim();
      var searchType = this.getAttribute('data-type');

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/events/search', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);

          // Determine which part of the response to use based on searchType
          var data = [];
          if (searchType === 'category') {
            data = response.categories || [];
          } else if (searchType === 'event') {
            data = response.events || [];
          } else if (searchType === 'account') {
            data = response.users || [];
          }

          // Ensure the data is an array and contains results
          if (!Array.isArray(data) || data.length === 0) {
            // Return a row with "No results found" if no results are found
            const noResultsRow = `<tr>
            <td style="text-align: center; padding: 20px; font-weight: bold;">
              No ${searchType} found.
            </td>
          </tr>`;
            // Update the appropriate table with the no results row
            const resultTableId = searchType === 'category' ? 'categoryResults' :
              searchType === 'event' ? 'eventResults' :
              'userResults';
            document.getElementById(resultTableId).innerHTML = noResultsRow;
            return;
          }

          // Determine which table to update based on searchType
          var resultTableId = searchType === 'category' ? 'categoryResults' :
            searchType === 'event' ? 'eventResults' :
            'userResults';
          updateTable(resultTableId, data);
        }
      };

      xhr.send(`search=${encodeURIComponent(searchTerm || '')}&type=${searchType}`);
    });
  });

  function updateTable(elementId, data) {
    var tableBody = document.getElementById(elementId);
    if (!tableBody) {
      console.error(`Element with ID ${elementId} not found.`);
      return;
    }

    tableBody.innerHTML = '';

    data.forEach(item => {
      var row = '<tr>';
      var fullName = item.first_name + ' ' + item.last_name;

      if (elementId === 'userResults') {
        row += '<td>' + fullName + '</td>';
        row += '<td>' + item.email + '</td>';
        row += '<td>' + item.phone + '</td>';
        row += '<td>' + item.user_role + '</td>';
        row += '<td>' + formatDate(item.datecreated) + '</td>';

        // Edit and delete buttons for users dynamically using userId
        row += `
      <td>
        <a class="edit-btn" href="/users/view?userId=${item.userId}"><span class="material-icons-outlined">edit</span></a>
        <a class="delete-btn" onclick="confirmDelete(event, ${item.userId}, 'user')">
        <span class="material-icons-outlined">delete</span></a>
      </td>
    `;
      } else if (elementId === 'categoryResults') {
        row += '<td>' + item.category_name + '</td>';
        row += '<td>' + formatDate(item.datecreate) + '</td>';
        row += '<td>' + (item.dateupdate ? formatDate(item.dateupdate) : 'No update yet') + '</td>';
        row += `
      <td>
        <a class="edit-btn" href="/category/save?categoryId=${item.categoryId}"><span class="material-icons-outlined">edit</span></a>
        <a class="delete-btn" onclick="confirmDelete(event, ${item.categoryId}, 'category')">
        <span class="material-icons-outlined">delete</span></a>
      </td>
    `;

      } else if (elementId === 'eventResults') {
        row += '<td>' + item.title + '</td>';
        row += '<td>' + item.location + '</td>';
        row += '<td>' + formatDate(item.event_date) + '</td>';
        row += '<td>' + item.category_name + '</td>';
        row += '<td>' + fullName + '</td>';
        row += `
      <td>
        <a class="edit-btn" href="/events/view?eventId=${item.eventId}"><span class="material-icons-outlined">edit</span></a>
        <a class="delete-btn" onclick="confirmDelete(event, ${item.eventId}, 'event')">
        <span class="material-icons-outlined">delete</span></a>
      </td>
    `;

      }

      row += '</tr>';
      tableBody.innerHTML += row;
    });
    console.log(`Table ${elementId} updated successfully.`);
  }
</script>