<?php

namespace Controllers;

class UsersController
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
        $registrationSuccess = false;
        $loggedinSuccess = false;
        $loggedoutSuccess = false;

        // // Check if registration success session variable is set
        // if (isset($_SESSION['registrationSuccess']) && $_SESSION['registrationSuccess']) {
        //     // Unset the session variable to avoid displaying the modal on subsequent requests
        //     unset($_SESSION['registrationSuccess']);
        //     $registrationSuccess = true;
        // }

        // // Check if login success session variable is set
        // if (isset($_SESSION['loginSuccess']) && $_SESSION['loginSuccess']) {
        //     // Unset the session variable to avoid displaying the modal on subsequent requests
        //     unset($_SESSION['loginSuccess']);
        //     $loggedinSuccess = true;
        // }

        // Check if logout success session variable is set
        if (isset($_SESSION['loggedout']) && $_SESSION['loggedout']) {
            // Unset the session variable to avoid displaying the modal on subsequent requests
            unset($_SESSION['loggedout']);
            $loggedoutSuccess = true;
        }

        // $auctioncats = $this->lot_auctionTable->findAllDistinctAuctions();

        // $auction_cat_bids = $this->user_bid_categoryTable->findAll();

        return [
            'template' => 'home.php',
            'variables' => [
                // 'auction_cat_bids' => $auction_cat_bids,
                'registrationSuccess' => $registrationSuccess,
                'loggedinSuccess' => $loggedinSuccess,
                'loggedoutSuccess' => $loggedoutSuccess,
                // 'auctioncats' => $auctioncats,
            ],
            'title' => 'Eventify - Discover Events'
        ];
    }
    public function search(): array
    {
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

        $users = $this->userTable->findAll();
        $userResults = $this->userTable->searchUsers($searchTerm);

        return [
            'template' => 'usersearch.html.php',
            'variables' => [
                'searchTerm' => $searchTerm,
                'users' => $users,
                'userResults' => $userResults,
            ],
            'title' => 'Eventify - Discover Events',
        ];
    }

    public function save(): array
    {
        $message = '';

        // Check if updating or creating a user
        $userId = $_POST['userId'] ?? ($_GET['userId'] ?? null);
        $isUpdate = !empty($userId);
        $existingUser = $isUpdate ? $this->userTable->find('userId', $userId)[0] : null;
        $existingProfilePic = $existingUser['profile_pic'] ?? null;

        // Allowed file extensions for profile picture
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        $uploadDir = "images/profile_pics/";

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Handle form submission
        if (isset($_POST['submit'])) {
            // Validate email
            $email = $_POST['email'];
            $existingEmail = $this->userTable->find('email', $email);

            // If an email is found
            if (!empty($existingEmail)) {
                // Get the first matching record
                $existingEmail = $existingEmail[0];

                // Allow if the found email belongs to the current user
                if ($isUpdate && $existingEmail['userId'] == $userId) {
                    // Email belongs to the current user, proceed with saving
                } else {
                    // Email belongs to another user, reject it
                    $message = 'Account exists already';
                    $_SESSION['errorMessage'] = $message;
                    return [
                        'template' => 'register.php',
                        'variables' => ['message' => $message],
                        'title' => 'Save User - Eventify'
                    ];
                }
            }

            // Handle password
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeat_password'];

            if (!empty($password)) {
                if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
                    $message = 'Password must be at least 8 characters long and contain at least one number';
                    $_SESSION['errorMessage'] = $message;
                    return [
                        'template' => 'register.php',
                        'variables' => ['message' => $message],
                        'title' => 'Save User - Eventify',
                    ];
                }

                if ($password !== $repeatPassword) {
                    $message = 'Passwords don\'t match';
                    $_SESSION['errorMessage'] = $message;
                    return [
                        'template' => 'register.php',
                        'variables' => ['message' => $message],
                        'title' => 'Save User - Eventify',
                    ];
                }

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // If password is empty, use the existing password
                $passwordHash = $existingUser['password'];
            }

            // Prepare user data
            $values = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $passwordHash,
                'phone' => $_POST['phone'],
            ];

            // Handle profile picture upload
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] !== "") {
                $profilePicName = $existingProfilePic;
                $uploadValid = true;

                $targetPath = $uploadDir . basename($_FILES['profile_pic']['name']);
                $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

                // Validate file extension
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $message = 'Invalid file format. Please choose a valid image.';
                    $_SESSION['errorMessage'] = $message;
                    $uploadValid = false;
                } else {
                    // Validate image
                    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
                    if ($check === false) {
                        $message = 'Uploaded file is not a valid image.';
                        $_SESSION['errorMessage'] = $message;
                        $uploadValid = false;
                    } else {
                        // Upload image
                        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {
                            $profilePicName = $_FILES["profile_pic"]["name"];
                        } else {
                            $message = 'Failed to upload image. Please try again.';
                            $_SESSION['errorMessage'] = $message;
                            $uploadValid = false;
                        }
                    }
                }

                // If profile pic is valid, add to user values
                if ($uploadValid) {
                    $values['profile_pic'] = $profilePicName;
                }
            }

            // Update or create user
            if ($isUpdate) {
                $values['userId'] = $userId;
                $values['dateupdated'] = date('Y-m-d H:i');
                $updated = $this->userTable->update($values);

                // Fetch updated user details and update session
                $updatedUser = $this->userTable->find('userId', $userId);
                if (!empty($updatedUser)) {
                    $_SESSION['userDetails'] = $updatedUser[0];
                }

                if (!$updated) {
                    $_SESSION['userUpdateSuccess'] = true;
                    header("Location: /users/view?userId=$userId");
                    exit;
                } else {
                    $message = 'Failed to update user. Please try again.';
                    $_SESSION['errorMessage'] = $message;
                }
            } else {
                $values['datecreated'] = date('Y-m-d H:i');
                $inserted = $this->userTable->insert($values);
                if (!$inserted) {
                    // Check if userDetails are available in the session
                    if (isset($_SESSION['userDetails'])) {
                        $_SESSION['userCreationSuccess'] = true;
                        header('Location: /events/dashboard');
                    } else {
                        $_SESSION['registrationSuccess'] = true;
                        header('Location: /users/logout');
                    }
                    exit;
                } else {
                    $message = 'Failed to create user. Please try again.';
                    $_SESSION['errorMessage'] = $message;
                }
            }
        }
        $user = $isUpdate ? [$existingUser] : null;

        return [
            'template' => 'register.php',
            'variables' => [
                'user' => $user[0],
                'message' => $message,
            ],
            'title' => $isUpdate ? 'Edit Account Details - Eventify' : 'Register - Eventify',
        ];
    }


    public function view()
    {
        // Check if 'userId' is set and not empty
        if (isset($_GET['userId']) && !empty($_GET['userId'])) {
            $userId = $_GET['userId'];
            $user = $this->userTable->find('userId', $userId);

            if ($user && !empty($user)) {
                return [
                    'template' => 'profile.php',
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
            'template' => 'login.php',
            'title' => 'Login - Eventify',
            'variables' => ['show_message' => $show_message]
        ];
    }
    public function delete()
    {
        $message = '';

        if (isset($_GET['userId']) && !empty($_GET['userId'])) {
            $userId = $_GET['userId'];

            // Check if the user is logged in and has a valid role
            if (isset($_SESSION['userDetails']) && $_SESSION['userDetails']['user_role']) {
                $currentUserRole = $_SESSION['userDetails']['user_role'];
            } else {
                $currentUserRole = null;
            }

            // Check if the user exists before attempting deletion
            $user = $this->userTable->find('userId', $userId);

            if ($user) {
                $this->userTable->delete($userId);
                $_SESSION['userDeletionSuccess'] = true;

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

                // Redirect based on the current user's role
                if ($currentUserRole === 'ADMIN') {
                    header("Location: /events/dashboard");
                    exit();
                } elseif ($currentUserRole === 'USER') {
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
        } else {
            // No userId provided in the request
            $_SESSION['deleteError'] = "Invalid request!";
            header("Location: /users");
            exit();
        }
    }
    public function session()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function startSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function checkLogin()
    {
        $this->startSession();

        if (!isset($_SESSION['userDetails']['user_role'])) {
            $this->redirectToLogin();
        }
    }


    public function logout()
    {
        $this->startSession();

        session_unset();
        session_destroy();

        $_SESSION['loggedout'] = true;

        $this->redirectToLogin();
    }

    private function redirectToLogin()
    {
        header("Location: /users/login");
        exit();
    }
}
