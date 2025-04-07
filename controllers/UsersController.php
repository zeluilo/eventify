<?php

namespace Controllers;

require_once __DIR__ . '/BaseController.php';

class UsersController extends BaseController
{
    private $userTable;
    private $eventTable;

    public function __construct($userTable, $eventTable)
    {
        $this->userTable = $userTable;
        $this->eventTable = $eventTable;
    }

    public function home()
    {
        // Ensure $events is always an array
        $events = $this->eventTable->findAll() ;
        if (!is_array($events)) {
            $events = [];
        }
    
        // Get current date
        $currentDate = date('Y-m-d');
    
        // Categorize events
        $upcomingEvents = [];
        $ongoingEvents = [];
        $pastEvents = [];
    
        foreach ($events as $event) {
            if (!isset($event['event_date'])) {
                continue; // Skip if event date is missing
            }
    
            $eventDate = date('Y-m-d', strtotime($event['event_date']));
            $daysSinceEvent = (strtotime($currentDate) - strtotime($eventDate)) / (60 * 60 * 24);
            if ($eventDate > $currentDate) {
                $upcomingEvents[] = $event; // Future event
            } elseif ($daysSinceEvent >= 0 && $daysSinceEvent <= 10) {
                $ongoingEvents[] = $event; // Ongoing event within 10 days
            } else {
                $pastEvents[] = $event; // Past event
            }          
        }
    
        // Ensure these are arrays (not null)
        $upcomingEvents = is_array($upcomingEvents) ? $upcomingEvents : [];
        $ongoingEvents = is_array($ongoingEvents) ? $ongoingEvents : [];
        $pastEvents = is_array($pastEvents) ? $pastEvents : [];
    
        // Sort each category only if they are not empty
        if (!empty($upcomingEvents)) {
            usort($upcomingEvents, fn($a, $b) => strtotime($a['event_date']) - strtotime($b['event_date']));
        }
        if (!empty($ongoingEvents)) {
            usort($ongoingEvents, fn($a, $b) => strtotime($a['event_date']) - strtotime($b['event_date']));
        }
        if (!empty($pastEvents)) {
            usort($pastEvents, fn($a, $b) => strtotime($b['event_date']) - strtotime($a['event_date'])); // Descending order
        }
    
        // Limit each category to 8 events
        $upcomingEvents = array_slice($upcomingEvents, 0, 8);
        $ongoingEvents = array_slice($ongoingEvents, 0, 8);
        $pastEvents = array_slice($pastEvents, 0, 8);
    
        return [
            'template' => 'client/home.php',
            'title' => 'Eventify - Discover Events',
            'variables' => [
                'upcomingEvents' => $upcomingEvents,
                'ongoingEvents' => $ongoingEvents,
                'pastEvents' => $pastEvents
            ]
        ];
    }
    public function save(): array
    {
        $message = '';
        $uuId = $_POST['uuId'] ?? ($_GET['uuId'] ?? null);
        $isUpdate = !empty($uuId);
        $existingUser = $isUpdate ? $this->userTable->find('uuId', $uuId)[0] ?? null : null;

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $existingEmail = $this->userTable->find('email', $email);

            // If an email is found
            if (!empty($existingEmail)) {
                // Get the first matching record
                $existingEmail = $existingEmail[0];

                // Allow if the found email belongs to the current user
                if ($isUpdate && $existingEmail['uuId'] === $uuId) {
                    // Email belongs to the current user, proceed with saving
                } else {
                    // Email belongs to another user, reject it
                    $this->handleError('Account exists already');
                    return [
                        'template' => 'auth/register.php',
                        'variables' => ['message' => $message],
                        'title' => 'Save User - Eventify'
                    ];
                }
            }
            // Handle password change
            $password = $_POST['password'] ?? '';
            $repeatPassword = $_POST['repeat_password'] ?? '';

            // Ensure session user ID matches before allowing password change
            if (empty($password)) {
                if ($_SESSION['userDetails']['uuId'] !== $uuId) {
                    // If password is empty and session user ID ≠ updating user ID, retain old password
                    $passwordHash = $existingUser['password'];
                } else {
                    // If user is updating their own account but left password empty, also retain old password
                    $passwordHash = $existingUser['password'];
                }
            } else {
                // Validate and update password if it's not empty
                if (isset($_SESSION['userDetails']) && $_SESSION['userDetails']['uuId'] === $uuId) {
                    $passwordHash = $this->handlePassword($existingUser, $password, $repeatPassword);
                    if (!$passwordHash) {
                        return [
                            'template' => 'auth/register.php',
                            'variables' => ['message' => $_SESSION['errorMessage']],
                            'title' => 'Save User - Eventify',
                        ];
                    }
                } else {
                    $passwordHash = $this->handlePassword($existingUser, $password, $repeatPassword);
                    if (!$passwordHash) {
                        return [
                            'template' => 'auth/register.php',
                            'variables' => ['message' => $_SESSION['errorMessage']],
                            'title' => 'Save User - Eventify',
                        ];
                    }
                }
            }

            $uuId = empty($existingUser['uuId']) ? bin2hex(random_bytes(16)) : $existingUser['uuId'];

            $values = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $passwordHash,
                'phone' => $_POST['phone'],
                'uuId' => $uuId,
                'profile_pic' => $this->handleProfilePicture($existingUser['profile_pic'] ?? null),
            ];
            $userId = $existingEmail['uuId'];
            // Call the updateUser or createUser method without expecting a return value
            if ($isUpdate) {
                $this->updateUser($uuId, $userId, $values);
            } else {
                $this->createUser($values); // No return expected
            }
        }
        $user = $isUpdate ? [$existingUser] : null;

        return [
            'template' => 'auth/register.php',
            'variables' => [
                'user' => $user ? $user[0] : null,
                'message' => $message,
            ],
            'title' => $isUpdate ? 'Edit Account Details - Eventify' : 'Register - Eventify',
        ];
    }
    private function handlePassword($existingUser, $password, $repeatPassword)
    {
        if (empty($password)) {
            return $existingUser['password'];
        }

        if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
            $_SESSION['errorMessage'] = 'Password must be at least 8 characters long and contain at least one number';
            return false;
        }

        if ($password !== $repeatPassword) {
            $_SESSION['errorMessage'] = 'Passwords don\'t match';
            return false;
        }

        return password_hash($password, PASSWORD_DEFAULT);
    }
    private function handleProfilePicture($existingProfilePic)
    {
        // Allowed file extensions for profile picture
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        $uploadDir = "images/profile_pics/";

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Check if a new profile picture is uploaded
        if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['name'] === "") {
            return $existingProfilePic;
        }

        $targetPath = $uploadDir . basename($_FILES['profile_pic']['name']);
        $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        // Validate file extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['errorMessage'] = 'Invalid file format. Please choose a valid image.';
            return $existingProfilePic;
        }

        // Validate image file
        if (!getimagesize($_FILES["profile_pic"]["tmp_name"])) {
            $_SESSION['errorMessage'] = 'Uploaded file is not a valid image.';
            return $existingProfilePic;
        }

        // Move uploaded file to destination
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {
            return $_FILES["profile_pic"]["name"]; // Return new profile picture name
        } else {
            $_SESSION['errorMessage'] = 'Failed to upload image. Please try again.';
            return $existingProfilePic;
        }
    }
    private function updateUser($uuId, $userId, $values): void
    {
        $values['uuId'] = $uuId;
        $values['userId'] = $userId;
        $values['dateupdated'] = date('Y-m-d H:i');
        $updated = $this->userTable->update($values);

        // Fetch updated user details and update session
        $updatedUser = $this->userTable->find('uuId', $uuId);

        if (!empty($updatedUser) && $_SESSION['userDetails']['uuId'] === $uuId) {
            $_SESSION['userDetails'] = $updatedUser[0];
        }

        if (!$updated) { // Ensure update was successful before redirecting
            $_SESSION['userUpdateSuccess'] = true;
            header("Location: /users/view?uuId=$uuId"); // Fixed array reference syntax
            exit;
        } else {
            $this->handleError('Failed to update user. Please try again.');
        }
    }
    private function createUser($values)
    {
        $values['datecreated'] = date(format: 'Y-m-d H:i');
        $values['user_role'] = 'USER';
        $inserted = $this->userTable->insert($values);
        if (!$inserted) {
            // Check if userDetails are available in the session
            if (isset($_SESSION['userDetails'])) {
                $_SESSION['userCreationSuccess'] = true;
                header('Location: /events/dashboard');
            } else {
                $_SESSION['registrationSuccess'] = true;
                header('Location: /users/save');
            }
            exit;
        } else {
            $this->handleError('Failed to create user. Please try again.');
        }
    }
    public function login(): array
    {
        $show_message = '';
        if (isset($_POST['submit'])) {
            $email = $this->userTable->find('email', $_POST['email']);
            // echo "<script>console.log('User Details: ', " . json_encode($email) . ");</script>";

            if ($email) {
                $verify_pw = password_verify($_POST['password'], $email[0]['password']);
                if ($verify_pw) {
                    $_SESSION['loginSuccess'] = true;
                    $_SESSION['loggedin'] = $email[0]['userId'];
                    $_SESSION['userDetails'] = $email[0];
                    header("Location: /users/home");
                    exit();
                } else {
                    $show_message = 'Incorrect login details. Please try again!';
                    $_SESSION['errorMessage'] = $show_message;
                }
                $_SESSION['loginSuccess'] = true;
            } else {
                $show_message = 'Incorrect login details. Please try again!';
                $_SESSION['errorMessage'] = $show_message;
            }
        }

        return [
            'template' => 'auth/login.php',
            'title' => 'Login - Eventify',
            'variables' => ['show_message' => $show_message]
        ];
    }
    public function view()
    {
        if (isset($_GET['uuId']) && !empty($_GET['uuId'])) {
            $userId = $_GET['uuId'];
            $user = $this->userTable->find('uuId', $userId);

            if ($user && !empty($user)) {
                return [
                    'template' => 'client/profile.php',
                    'variables' => [
                        'user' => $user[0],
                    ],
                    'title' => 'View Profile - Eventify'
                ];
            } else {
                $_SESSION['errorMessage'] = 'User not found. Please try again.';
                header('Location: /users/dashboard');
                exit;
            }
        } else {
            $_SESSION['errorMessage'] = 'Invalid user ID. Please try again.';
            header('Location: /users/dashboard');
            exit;
        }
    }
    public function delete()
    {
        $message = '';

        if (isset($_GET['userId']) && !empty($_GET['userId'])) {
            $userId = $_GET['userId'];

            // Check if the user exists before attempting deletion
            $user = $this->userTable->find('userId', $userId);

            // Store session details in a variable for comparison
            $currentUserId = trim($_SESSION['userDetails']['userId']);
            $deletingUserId = trim($userId);

            if ($user) {
                // Delete user
                $this->userTable->delete($userId);

                // Delete events related to the user
                $events = $this->eventTable->find('userId', $userId);
                if ($events) {
                    foreach ($events as $event) {
                        $this->eventTable->delete($event['eventId']);
                    }
                } else {
                    // Log if no events are found for the user
                    error_log("No events found for user ID: " . $userId);
                }

                // Now, compare before performing the deletion
                if ($currentUserId !== $deletingUserId) {
                    // If the current user is not deleting their own account, log the mismatch and redirect to the dashboard
                    $_SESSION['userDeletionSuccess'] = true;
                    header("Location: /events/dashboard");
                    exit();
                } elseif ($currentUserId === $deletingUserId) {
                    // If the session user ID matches the user being deleted, log them out
                    $_SESSION['userDeletionSuccess'] = true;
                    $this->logout();
                    exit();
                } else {
                    // Default logout if none of the conditions match
                    $_SESSION['userDeletionSuccess'] = true;
                    header("Location: /users/logout");
                    exit();
                }
            } else {
                // User not found
                $message = "User not found!";
                $_SESSION['errorMessage'] = $message;
                header("Location: /users/home");
                exit();
            }
        }
    }
    public function logout()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Clear all session variables
        $_SESSION = [];

        // Destroy the session
        session_unset();
        session_destroy();

        // Expire session cookie
        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        $this->redirectToLogin();
    }
}
