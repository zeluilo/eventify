<main id="event-menu" class="main-container" style="display: none;">
    <!-- <div class="main-title">
        <h2>Events</h2>
    </div> -->

    <div class="data-section">
        <div class="table-header">
            <h2>Event List</h2>
            <div class="table-header-right">
                <div class="search-bar">
                    <input type="text" name="search" class="searchInput" data-type="event" placeholder="Search events...">
                </div>
                <a class="action-btn add-btn" href="/events/save">
                    <span class="material-icons-outlined">add</span> Add New event
                </a>
            </div>
        </div>

        <table id="eventTable" class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Event Date</th>
                    <th>Category</th>
                    <th>Created by</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="eventResults">
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td> <?= htmlspecialchars($event['title']) ?> </td>
                        <td> <?= htmlspecialchars($event['location']) ?> </td>
                        <td> <?= htmlspecialchars(date('F j, Y', strtotime($event['event_date']))) ?> </td>
                        <td> <?= htmlspecialchars($event['category_name']) ?> </td>
                        <td> <?= htmlspecialchars($event['first_name'] . " " . $event['last_name']) ?> </td>
                        <td>
                            <a class="edit-btn" href="/events/view?eventId=<?= $event['eventId'] ?>"><span class="material-icons-outlined">edit</span></a>
                            <a class="delete-btn" onclick="confirmDelete(event, <?php echo $event['eventId']; ?>, 'event')">
                                <span class=" material-icons-outlined">delete</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <!-- More rows dynamically -->
            </tbody>
        </table>
    </div>
</main>