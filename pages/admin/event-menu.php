<main id="event-menu" class="main-container" style="display: none;">
    <!-- <div class="main-title">
        <h2>Events</h2>
    </div> -->

    <div class="data-section">
        <div class="table-header">
            <h2>Events List</h2>
            <button class="action-btn add-btn">
                <span class="material-icons-outlined">add</span> Add New Event
            </button>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Category</th>
                    <th>Creator (User ID)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Row - Replace dynamically later -->
                <tr>
                    <td>1</td>
                    <td>Annual Tech Summit</td>
                    <td>Discussing emerging technologies.</td>
                    <td>2025-04-12 10:00 AM</td>
                    <td>Hall A, Innovation Center</td>
                    <td><img src="uploads/tech-summit.jpg" alt="Event Image"
                            class="table-img"></td>
                    <td>2025-03-20</td>
                    <td>2025-03-21</td>
                    <td>Tech Conference</td>
                    <td>12</td>
                    <td>
                        <button class="action-btn edit-btn"><span
                                class="material-icons-outlined">edit</span></button>
                        <button class="action-btn delete-btn"><span
                                class="material-icons-outlined">delete</span></button>
                    </td>
                </tr>
                <!-- More rows dynamically -->
            </tbody>
        </table>
    </div>
</main>