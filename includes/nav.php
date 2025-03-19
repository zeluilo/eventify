<?php

// Check if the user is logged in or registered
$isLoggedInOrRegistered = isset($_SESSION['userDetails']);

// Check if the logged-in user's checkAdmin is "USER"
$isUser = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'USER';
$isAdmin = $isLoggedInOrRegistered && $_SESSION['userDetails']['user_role'] === 'ADMIN';

?>

<nav id="navmenu" class="navmenu">
    <ul>
        <li><a href="/users/home" class="active">Home</a></li>
        <li><a href="/users/home/#about">About</a></li>
        <li><a href="/category/create">Category</a></li>
        <li class="dropdown">
            <a href="/events/createEvent" class="dropdown-toggle"><span>Events</span> <i class="toggle-dropdown"></i></a>
            <ul class="dropdown-menu">
                <li><a href="/events/createEvent">Create an Event</a></li>
                <li><a href="#">View Available Events</a></li>
            </ul>
        </li>
        <li><a href="#contact">Contact</a></li>
        <!-- <li class="user-dropdown">
            <?php if (isset($_SESSION['userDetails'])) : ?>
                <?php $userDetails = $_SESSION['userDetails']; ?>
                <img class="user-avatar" src="../assets/images/profile.png" alt="User Avatar">

                <ul class="user-dropdown-menu">
                    <span class="user-name"><?= htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']) ?></span>
                    <div class="dropdown-divider"></div>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            <?php else : ?>
                <li><a href="/users/register">Sign In</a></li>
            <?php endif; ?>
        </li> -->
        <li class="dropdown">
            <?php if (isset($_SESSION['userDetails'])) : ?>
                <?php $userDetails = $_SESSION['userDetails']; ?>
                <a class="dropdown-toggle" href="#">
                    <img class="user-avatar" src="../assets/images/profile.png" alt="User Avatar">
                </a>
                <ul class="user-dropdown-menu">
                    <li>
                        <h5 class="user-name"><?= htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']) ?></h5>
                        <h6 class="user-name"><?= htmlspecialchars($userDetails['email']) ?></h6>

                    </li>
                    <div class="dropdown-divider"></div>
                    <li>
                        <a class="dropdown-item" href="/admin/edituser?userId=<?php echo $userDetails['userId'] ?>">
                            Profile
                        </a>
                    </li>
                    <?php if ($isAdmin) : ?>
                        <div class="dropdown-divider"></div>
                        <li>
                            <a class="dropdown-item" href="/admin/dashboard">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <div class="dropdown-divider"></div>
                    <li>
                        <a class="dropdown-item" href="#" onclick="confirmLogout(event)">Logout</a>
                    </li>
                </ul>
        </li>
    <?php else : ?>
        <li><a href="/users/login">Sign In</a></li>
    <?php endif; ?>
    </ul>
</nav>