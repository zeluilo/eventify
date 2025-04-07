<?php 

// Include error messages
include '../includes/error-message.php';
?>

<section id="home" class="home section dark-background">
    <div class="home-content">
        <h2>Events</h2>
        <p>Browse and filter events based on category.</p>
    </div>
</section>

<section id="events" class="events-section">
    <div class="search-bar">
        <input type="text" name="search" id="searchInput" placeholder="Search for available events...">
    </div>
    <div class="events-container">
        <div class="categories">
            <h4>Filter by Categories</h4>
            <button id="clearFilter" class="clear-filter-btn">Clear Filter</button>
            <div class="category-filter">
                <ul id="categoryList">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="#" class="category-link" data-category="<?= htmlspecialchars($category['category_name']) ?>">
                                <?= htmlspecialchars($category['category_name']) ?>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="events-list">
            <h4>List of Available Events</h4>
            <div class="events-cards" id="eventCards">
                <?php if (empty($events)): ?>
                    <p>No current events available.</p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="event-card">
                            <div class="event-item">
                                <h3><?= htmlspecialchars($event['title']) ?></h3>
                                <p class="event-date"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) ?></p>
                                <p class="event-location"><?= htmlspecialchars($event['location']) ?></p>

                                <div class="btn-wrap">
                                    <a class="btn-view" href="/events/view?eventId=<?= $event['eventId'] ?>">View Event</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>