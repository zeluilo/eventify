<?php

namespace Controllers;

// session_start();
class EventController
{
    private $categoryTable;
    private $eventTable;
    private $viewEventDetails;

    public function __construct($categoryTable, $eventTable, $viewEventDetails)
    {
        $this->categoryTable = $categoryTable;
        $this->eventTable = $eventTable;
        $this->viewEventDetails = $viewEventDetails;
    }

    public function dashboard()
    {

        $categories = $this->categoryTable->findAll();
        return [
            'template' => 'dashboard.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Eventify - Admin Dashboard'
        ];
    }

    public function view()
    {
        if (isset($_GET['eventId']) && !empty($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $event = $this->viewEventDetails->find('eventId', $eventId);
            echo "<script>console.log('Event Details: ', " . json_encode($event) . ");</script>";
            return [
                'template' => 'event-single.php',
                'variables' => [
                    'event' => $event[0],
                ],
                'title' => 'View Event - Eventify'
            ];
        } else {
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
    }
    public function fetchEventDetails()
    {
        if (isset($_GET['eventId']) && !empty($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $event = $this->viewEventDetails->find('eventId', $eventId);
            $categories = $this->categoryTable->findAll();
    
            // If the event is found, return the event data and categories as JSON
            if ($event) {
                echo json_encode([
                    'success' => true,
                    'event' => [
                        'eventId' => $event[0]['eventId'],
                        'title' => $event[0]['title'],
                        'description' => $event[0]['description'],
                        'event_date' => $event[0]['event_date'],
                        'location' => $event[0]['location'],
                        'categoryId' => $event[0]['categoryId'],
                    ],
                    'categories' => $categories,
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Event not found']);
            }
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Event ID is missing']);
            exit;
        }
    }
    public function save(): array
    {
        $category = $this->categoryTable->findAll();
        $events = $this->eventTable->findAll();
        $currentDateTime = date('Y-m-d\TH:i');
        $message = '';
    
        $eventId = $_POST['eventId'] ?? ($_GET['eventId'] ?? null);
        $isUpdate = !empty($eventId);
        $existingEvent = $isUpdate ? $this->eventTable->find('eventId', $eventId)[0] : null;
        $existingImage = $existingEvent['image'] ?? null;
    
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        $uploadDir = "images/events/";
    
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
    
        // Handle form submission
        if (isset($_POST['submit'])) {
            $imageName = $existingImage;
            $uploadValid = true;
    
            if (isset($_FILES['image']) && $_FILES['image']['name'] !== "") {
                $targetPath = $uploadDir . basename($_FILES['image']['name']);
                $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    
                // Validate extension
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $message = 'Invalid file format. Please choose a valid image.';
                    $_SESSION['errorMessage'] = $message;
                    $uploadValid = false;
                } else {
                    // Validate image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check === false) {
                        $message = 'Uploaded file is not a valid image.';
                        $_SESSION['errorMessage'] = $message;
                        $uploadValid = false;
                    } else {
                        // Upload image
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                            $imageName = $_FILES["image"]["name"];
                        } else {
                            $message = 'Failed to upload image. Please try again.';
                            $_SESSION['errorMessage'] = $message;
                            $uploadValid = false;
                        }
                    }
                }
            }
    
            if ($uploadValid) {
                $values = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'location' => $_POST['location'],
                    'event_date' => $_POST['event_date'],
                    'categoryId' => $_POST['categoryId'],
                    'image' => $imageName,
                    'userId' => $_SESSION['userDetails']["userId"]
                ];
    
                if ($isUpdate) {
                    $values['eventId'] = $eventId;
                    $values['dateupdate'] = date('Y-m-d H:i');
                    $this->eventTable->update($values);
                    $_SESSION['eventUpdateSuccess'] = true;
                    header('Location: /events/view');
                    exit;
                } else {
                    $values['datecreate'] = date('Y-m-d H:i');
                    $this->eventTable->insert($values);
                    $_SESSION['eventCreationSuccess'] = true;
                    header('Location: /events/view');
                    exit;
                }
            }
        }
    
        $event = $isUpdate ? [$existingEvent] : null;
    
        return [
            'template' => 'events.php',
            'variables' => [
                'currentDateTime' => $currentDateTime,
                'category' => $category,
                'message' => $message,
                'event' => $event,
                'events' => $events
            ],
            'title' => $isUpdate ? 'Edit Event - Eventify' : 'Create Event - Eventify'
        ];
    } 
    public function delete(): array
    {
        $message = '';
        if (isset($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $event = $this->eventTable->find('eventId', $eventId);
            if ($event) {
                // Delete the event if it exists
                $this->eventTable->delete($eventId);
                $_SESSION['eventDeletionSuccess'] = true;
                header('location: /events/view');
                exit();
            } else {
                // Event not found, show an error message
                $message = "Event not found.";
                $_SESSION['errorMessage'] = $message;
                header('location: /events/view');
                exit();
            }
        }
        header('location: /events/view');
        exit();
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
            echo '<a href="/events/view?eventId=' . $event['eventId'] . '">View Event</a>';
            echo '</div>';
        }

        if (empty($events)) {
            echo "<p>No events found.</p>";
        }
        exit;
    }

}
