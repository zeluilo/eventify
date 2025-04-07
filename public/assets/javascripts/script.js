(function () {
    "use strict";

    // ==============================
    // General Script Initialization
    // ==============================
    console.log("Script is really working!");
    console.log("AOS initialized:", typeof AOS !== "undefined");
    // AOS.refresh();

    // ==============================
    // SweetAlert Logout Confirmation
    // ==============================
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/users/logout';
            }
        });
    }

    function confirmDelete(event, itemId, itemType) {
        event.preventDefault();
        console.log(itemType + " ID:", itemId); // Log the item ID to check if it's correct

        let confirmationMessage = `Do you really want to delete the ${itemType}?`;
        // Check itemType and create the appropriate URL for deletion
        let deleteUrl = '';
        if (itemType === 'event') {
            deleteUrl = `/events/delete?eventId=${itemId}`;
        } else if (itemType === 'user') {
            deleteUrl = `/users/delete?uuId=${itemId}`;
        } else if (itemType === 'category') {
            deleteUrl = `/category/delete?categoryId=${itemId}`;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: confirmationMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, Delete ${itemType}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    }

    // ==============================
    // Set Minimum Date & Time for Event Input
    // ==============================
    document.addEventListener('DOMContentLoaded', function () {
        const eventDateTimeInput = document.getElementById("event_datetime");
        if (eventDateTimeInput) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            eventDateTimeInput.min = currentDateTime;
        }
    });

    // Function to format the event date and time
    function formatDate(eventDate) {
        const date = new Date(eventDate);
        return date.toLocaleString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    function togglePasswordVisibility() {
        let password = document.getElementById("password");
        let repeatPassword = document.getElementById("repeat_password");

        if (password.type === "password") {
            password.type = "text";
            if (repeatPassword) repeatPassword.type = "text";
        } else {
            password.type = "password";
            if (repeatPassword) repeatPassword.type = "password";
        }
    }
    // ==============================
    // Live Event Search (AJAX Filtering)
    // ==============================

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const categoryLinks = document.querySelectorAll('.category-link');
        const eventCardsContainer = document.getElementById('eventCards');
        const clearFilterButton = document.getElementById('clearFilter');
        let currentCategory = '';

        console.log("DOM fully loaded. Script running...");

        // Handle search input keyup
        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.trim();
            console.log(`Search input: "${searchTerm}" | Current Category: "${currentCategory}"`);
            fetchFilteredEvents(searchTerm, currentCategory);
        });

        // Handle category filter click
        categoryLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                currentCategory = this.getAttribute('data-category');
                const searchTerm = searchInput.value.trim();
                console.log(`Category selected: "${currentCategory}" | Search Term: "${searchTerm}"`);
                fetchFilteredEvents(searchTerm, currentCategory);
            });
        });

        //Handle Clear Filter button click
        clearFilterButton.addEventListener('click', function (e) {
            e.preventDefault();
            currentCategory = '';
            searchInput.value = '';
            fetchFilteredEvents('', currentCategory);
        });

        // AJAX request function
        function fetchFilteredEvents(search = '', category = '') {
            console.log(`Sending AJAX request...`);
            console.log(`Params sent => search: "${search}", category: "${category}"`);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/events/filter', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log(`AJAX response status: ${xhr.status}`);
                    if (xhr.status === 200) {
                        console.log('Response received. Updating event list...');
                        eventCardsContainer.innerHTML = xhr.responseText;
                    } else {
                        console.error('Failed to load events. Server error or wrong path.');
                    }
                }
            };

            const params = `search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`;
            xhr.send(params);
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const navSections = document.querySelectorAll('.nav-section');

        navSections.forEach(section => {
            section.querySelector('h3').addEventListener('click', () => {
                section.classList.toggle('active');

                console.log(`Toggled submenu for: ${section.querySelector('h3').innerText}`);
            });
        });
    });

    // document.addEventListener("DOMContentLoaded", function () {
    //     AOS.init({
    //         duration: 1000,
    //         once: false,
    //         mirror: false,
    //     });
    // });

    // $(document).ready(function () {
    //     AOS.init();
    // });

    /**
     * Navmenu Scrollspy
     */
    let navmenulinks = document.querySelectorAll('.navmenu a');

    function navmenuScrollspy() {
        navmenulinks.forEach(navmenulink => {
            if (!navmenulink.hash) return;
            let section = document.querySelector(navmenulink.hash);
            if (!section) return;
            let position = window.scrollY + 200;
            if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
                navmenulink.classList.add('active');
            } else {
                navmenulink.classList.remove('active');
            }
        })
    }
    window.addEventListener('load', navmenuScrollspy);
    document.addEventListener('scroll', navmenuScrollspy);

    /**
 * Apply .scrolled class to the body as the page is scrolled down
 */
    function toggleScrolled() {
        const selectBody = document.querySelector('body');
        const selectHeader = document.querySelector('#header');
        if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
        window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
    }

    document.addEventListener('scroll', toggleScrolled);
    window.addEventListener('load', toggleScrolled);

    /**
     * Mobile nav toggle
     */
    const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

    function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active');
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
    }
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

    /**
     * Hide mobile nav on same-page/hash links
     */
    document.querySelectorAll('#navmenu a').forEach(navmenu => {
        navmenu.addEventListener('click', () => {
            if (document.querySelector('.mobile-nav-active')) {
                mobileNavToogle();
            }
        });

    });

    document.addEventListener("DOMContentLoaded", function () {
        const mobileToggle = document.getElementById("mobile-nav-toggle");
        const navMenu = document.getElementById("navmenu");
        const dropdowns = document.querySelectorAll(".dropdown-toggle");
    
        // Toggle mobile nav menu
        mobileToggle.addEventListener("click", function () {
            navMenu.classList.toggle("active");
        });
    
        // Toggle dropdown menus
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener("click", function (event) {
                event.preventDefault();
                this.parentElement.classList.toggle("active");
            });
        });
    });

    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            preloader.remove();
        });
    }

    /**
     * Scroll top button
     */
    let scrollTop = document.querySelector('.scroll-top');

    function toggleScrollTop() {
        if (scrollTop) {
            window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
        }
    }
    scrollTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    window.addEventListener('load', toggleScrollTop);
    document.addEventListener('scroll', toggleScrollTop);

    /**
     * Animation on scroll function and init
     */
    function aosInit() {
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    }
    window.addEventListener('load', aosInit);

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
        input.addEventListener('keyup', function () {
            var searchTerm = this.value.trim();
            var searchType = this.getAttribute('data-type');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/events/search', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
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
          <a class="edit-btn" href="/users/view?uuId=${item.uuId}"><span class="material-icons-outlined">edit</span></a>
          <a class="delete-btn" onclick="confirmDelete(event, ${item.uuId}, 'user')">
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

})();

