<?php
namespace Controllers;

// session_start();
class EventController
{
    private $categoryTable;
    private $eventTable;


    public function __construct($categoryTable, $eventTable)
    {
        $this->categoryTable = $categoryTable;
        $this->eventTable = $eventTable;
    }

    public function view()
    {
        $events = $this->eventTable->findAll();
        $categories = $this->categoryTable->findAll();

        return [
            'template' => 'event-menu.php',
            'variables' => [
                'events' => $events,
                'categories' => $categories,
            ],
            'title' => 'Event Menu - Eventify'
        ];
    }

    public function save(): array
    {
        $category = $this->categoryTable->findAll();
        $events = $this->eventTable->findAll();
        $currentDateTime = date('Y-m-d\TH:i');
        $message = '';
        // echo "<script>console.log('User Details: ' + " . json_encode($_SESSION['userDetails']) . ");</script>";
    
        // Allowed extensions for image uploads
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
    
        // Check if the event ID is set for editing an event
        $eventId = isset($_GET['eventId']) ? $_GET['eventId'] : null;
    
        if (isset($_FILES["image"])) {
            $for_directory = "images/events/";
    
            // Check if the directory exists, if not, create it
            if (!is_dir($for_directory)) {
                mkdir($for_directory, 0775, true);
            }
    
            // Get the file extension of the image
            $for_pic = $for_directory . basename($_FILES["image"]["name"]);
            $picType = strtolower(pathinfo($for_pic, PATHINFO_EXTENSION));
    
            // Check if the file extension is allowed
            if (!in_array($picType, $allowedExtensions)) {
                $message = 'Invalid file format. Please choose a valid image.';
                $_SESSION['errorMessage'] = $message;
            } else {
                // Validate the image file
                $validate = getimagesize($_FILES["image"]["tmp_name"]);
                if ($validate === false) {
                    $message = 'Invalid image file format. Please choose a valid image.';
                    $_SESSION['errorMessage'] = $message;
                } else {
                    if (isset($_POST['submit'])) {
                        // Attempt to move the uploaded file
                        $browse = move_uploaded_file($_FILES["image"]["tmp_name"], $for_pic);
    
                        if (!$browse) {
                            $message = 'Failed to upload image. Please try again.';
                            $_SESSION['errorMessage'] = $message;
                        } else {
                            $values = [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'location' => $_POST['location'],
                                'event_date' => $_POST['event_date'],
                                'categoryId' => $_POST['categoryId'],
                                'image' => $_FILES["image"]["name"],
                                'datecreate' => date('Y-m-d H:i'),
                                'userId' => $_SESSION['userDetails']["userId"]
                            ];
    
                            // If eventId is set, we're editing an existing event
                            if ($eventId) {
                                $updateSuccess = $this->eventTable->update($eventId, $values);
                                if ($updateSuccess) {
                                    $message = 'Event updated successfully!';
                                    $_SESSION['eventUpdateSuccess'] = true;
                                } else {
                                    $message = 'Failed to update event. Please try again.';
                                    $_SESSION['errorMessage'] = $message;
                                }
                            } else {
                                // If no eventId, we're creating a new event
                                $inserted = $this->eventTable->insert($values);
    
                                if ($inserted) {
                                    $message = 'Failed to create event. Please try again.';
                                    $_SESSION['errorMessage'] = $message;
                                } else {
                                    $_SESSION['eventCreationSuccess'] = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    
        // Fetch the existing event data if we're editing
        if ($eventId) {
            $event = $this->eventTable->find('eventId', $eventId);
        } else {
            $event = null;
        }
        
    
        return [
            'template' => 'events.php',
            'variables' => [
                'currentDateTime' => $currentDateTime,
                'category' => $category,
                'message' => $message,
                'event' => $event,
                'events' => $events
            ],
            'title' => $eventId ? 'Edit Event - Eventify' : 'Create Event - Eventify'
        ];
    }

    public function filter()
{
    $search = $_POST['search'] ?? '';
    $category = $_POST['category'] ?? '';

    $events = [];

    if (!empty($search)) {
        $events = $this->eventTable->searchEvents($search);
    } elseif (!empty($category)) {
        $events = $this->eventTable->searchCategory($category);
    } else {
        $events = $this->eventTable->findAll();
    }

    foreach ($events as $event) {
        echo '<div class="event-card">';
        echo '<h5>' . htmlspecialchars($event['title']) . '</h5>';
        echo '<p><strong>Date:</strong> ' . htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['location']) . '</p>';
        echo '<a href="/event/view/' . $event['eventId'] . '">View Event</a>';
        echo '</div>';
    }

    if (empty($events)) {
        echo "<p>No events found.</p>";
    }

    exit;
}

}
    