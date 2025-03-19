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
            <div class="category-filter">
                <ul>
                    <?php
                    foreach ($categories as $category) {
                        echo '<li><a href="/events?category=' . $category['categoryId'] . '">' . $category['category_name'] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="events-list">
        <h4>List of Available Events</h4>
            <div class="event-cards">
                <?php
                if (empty($events)) {
                    echo "<p>No current events available.</p>";
                } else {
                    foreach ($events as $event) {
                        echo '<div class="event-card">';
                        echo '<h5>' . htmlspecialchars($event['title']) . '</h5>';
                        echo '<p><strong>Date:</strong> ' . htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) . '</p>';
                        echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['location']) . '</p>';
                        echo '<a href="/event/view/' . $event['eventId'] . '">View Event</a>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>