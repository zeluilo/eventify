<?php
$isLoggedInOrRegistered = isset($_SESSION['userDetails']);
$isUser = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'USER';
$isAdmin = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'ADMIN';
?>

<!-- Mobile Navigation Toggle Button -->
<div id="mobile-nav-toggle" class="mobile-nav-toggle">&#9776;</div>

<nav id="navmenu" class="navmenu">
    <ul class="nav-list">
        <li><a href="/users/home" class="active">Home</a></li>
        <li><a href="/users/home#about">About</a></li>
        <li class="dropdown">
            <a href="/events/view" class="dropdown-toggle">Events <i class="toggle-dropdown"></i></a>
            <?php if ($isAdmin || $isUser) : ?>
                <ul class="dropdown-menu">
                    <li><a href="/events/save">Create an Event</a></li>
                    <li><a href="/events/view">View Available Events</a></li>
                </ul>
            <?php endif ?>
        </li>
        <li><a href="#contact">Contact</a></li>

        <!-- User Dropdown -->
        <li class="dropdown user-dropdown">
            <?php if (isset($_SESSION['userDetails'])) : ?>
                <?php $userDetails = $_SESSION['userDetails']; ?>
                <a class="dropdown-toggle" href="#">
                    <img class="dropdown user-avatar"
                        src="<?php echo !empty($userDetails['profile_pic']) ? '../images/profile_pics/' . $userDetails['profile_pic'] : '../assets/images/profile.png'; ?>"
                        alt="User Avatar">
                </a>
                <ul class="user-dropdown-menu">
                    <li>
                        <h5 class="user-name"><?= htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']) ?></h5>
                        <h6 class="user-email"><?= htmlspecialchars($userDetails['email']) ?></h6>
                    </li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item" href="/users/view?uuId=<?php echo $userDetails['uuId'] ?>">Profile</a></li>
                    <?php if ($isAdmin) : ?>
                        <div class="dropdown-divider"></div>
                        <li><a class="dropdown-item" href="/events/dashboard">Dashboard</a></li>
                    <?php endif; ?>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item" href="#" onclick="confirmLogout(event)">Logout</a></li>
                </ul>
            <?php else : ?>
        <li><a href="/users/login">Sign In</a></li>
    <?php endif; ?>
    </li>
    </ul>
</nav>

<style>
    /*--------------------------------------------------------------
# Mobile Navmenu
--------------------------------------------------------------*/

    @media (max-width: 1199px) {

        /* Mobile Toggle Button */
        .mobile-nav-toggle {
            display: block;
            font-size: 28px;
            cursor: pointer;
            color: var(--nav-color);
            padding: 10px;
            position: absolute;
            top: 10px;
            right: 20px;
            z-index: 1000;
        }

        .navmenu {
            position: fixed;
            top: 0;
            right: 100%;
            width: 250px;
            height: auto;
            background-color: var(--nav-mobile-background-color);
            transition: left 0.3s fade-in;
            z-index: 999;
            padding: 10px 0;
            margin: 70px 20px 0 0;

        }

        .navmenu.active {
            right: 0;
        }

        .navmenu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navmenu li {
            padding: 15px 20px;
        }

        .navmenu a {
            text-decoration: none;
            font-size: 17px;
            color: var(--nav-dropdown-color);
            display: block;
        }

        .navmenu a:hover {
            color: var(--nav-dropdown-hover-color);
        }

        /* Mobile User Avatar */
        .user-avatar {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
            display: inline-block;
            vertical-align: middle;
        }

        .user-name, .user-email{
            color: var(--nav-dropdown-color);
        }

        /* Dropdown Menu for Mobile */
        .dropdown-menu,
        .user-dropdown-menu {
            position: static;
            display: none;
            z-index: 99;
            padding: 10px 0;
            margin: 10px 20px;
            background-color: var(--nav-dropdown-background-color);
            border: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
            box-shadow: none;
            transition: all .5s ease-in-out;
        }

        .dropdown.active>.dropdown-menu,
        .user-dropdown.active>.user-dropdown-menu {
            display: block;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mobileToggle = document.getElementById("mobile-nav-toggle");
        const navMenu = document.getElementById("navmenu");
        const dropdowns = document.querySelectorAll(".dropdown-toggle");

        // Toggle mobile nav menu
        mobileToggle.addEventListener("click", function() {
            navMenu.classList.toggle("active");
        });

        // Toggle dropdown menus
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener("click", function(event) {
                event.preventDefault();
                this.parentElement.classList.toggle("active");
            });
        });
    });
</script>