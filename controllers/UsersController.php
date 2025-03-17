<?php

namespace Controllers;

session_start();
class UsersController
{
    private $userTable;

    public function __construct($userTable)
    {
        $this->userTable = $userTable;
    }

    public function home()
    {
        $registrationSuccess = false;
        $loggedinSuccess = false;
        $loggedoutSuccess = false;

        // Check if registration success session variable is set
        if (isset($_SESSION['registrationSuccess']) && $_SESSION['registrationSuccess']) {
            // Unset the session variable to avoid displaying the modal on subsequent requests
            unset($_SESSION['registrationSuccess']);
            $registrationSuccess = true;
        }

        // Check if login success session variable is set
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            // Unset the session variable to avoid displaying the modal on subsequent requests
            unset($_SESSION['loggedin']);
            $loggedinSuccess = true;
        }

        // Check if logout success session variable is set
        if (isset($_SESSION['loggedout']) && $_SESSION['loggedout']) {
            // Unset the session variable to avoid displaying the modal on subsequent requests
            unset($_SESSION['loggedout']);
            $loggedoutSuccess = true;
        }

        // $auctioncats = $this->lot_auctionTable->findAllDistinctAuctions();

        // $auction_cat_bids = $this->user_bid_categoryTable->findAll();

        return [
            'template' => 'userhome.html.php',
            'variables' => [
                // 'auction_cat_bids' => $auction_cat_bids,
                // 'registrationSuccess' => $registrationSuccess,
                // 'loggedinSuccess' => $loggedinSuccess,
                // 'loggedoutSuccess' => $loggedoutSuccess,
                // 'auctioncats' => $auctioncats,
            ],
            'title' => 'Eventify'
        ];
    }
    public function editUser(): array
    {
        $userId = isset($_GET['userId']) ? $_GET['userId'] : null;

        if (isset($_POST['submit'])) {
            $values = [
                'userId' => $_POST['userId'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'user_role' => $_SESSION['userDetails']["user_role"]
            ];

            $_SESSION['userDetails'] = [
                'userId' => $_POST['userId'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'user_role' => $_SESSION['userDetails']["user_role"]
            ];

            $this->userTable->update($values);
            header('location: /home');
            exit();
        }

        $user = $this->userTable->find('userId', $userId)[0];

        return [
            'template' => 'edituser.php',
            'variables' => [
                'user' => $user
            ],
            'title' => 'Edit Account'
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
            'title' => 'Eventify',
        ];
    }

    public function register(): array
    {
        $error = '';

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];

            $existingEmail = $this->userTable->find('email', $email);
            if (!empty($existingEmail)) {
                $error = 'Account exists already';
                return [
                    'template' => 'register.php',
                    'variables' => ['error' => $error],
                    'title' => 'Create Account'
                ];
            }

            $password = $_POST['password'];
            $repeatPassword = $_POST['repeat_password'];

            if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
                $error = 'Password must be at least 8 characters long and contain at least one number';
                return [
                    'template' => 'register.php',
                    'variables' => ['error' => $error],
                    'title' => 'Create Account',
                ];
            }

            if ($password !== $repeatPassword) {
                $error = 'Passwords don\'t match';
                return [
                    'template' => 'register.php',
                    'variables' => ['error' => $error],
                    'title' => 'Create Account',
                ];
            }

            $pw = password_hash($password, PASSWORD_DEFAULT);

            $values = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $pw,
                'phone' => $_POST['phone'],
                'user_role' => 'USER',
                'datecreated' => date('Y-m-d H:i'),
            ];

            $_SESSION['registrationSuccess'] = true;


            $this->userTable->insert($values);
            header("Location: /login");
            exit();
        }

        return [
            'template' => 'register.php',
            'variables' => ['error' => $error],
            'title' => 'Register on Eventify!',
        ];
    }
    public function login(): array
    {
        $show_message = '';
        if (isset($_POST['submit'])) {
            $email = $this->userTable->find('email', $_POST['email']);
            if ($email) {
                $verify_pw = password_verify($_POST['password'], $email[0]['password']);
                if ($verify_pw) {
                    $_SESSION['loggedin'] = $email[0]['userId'];
                    $_SESSION['userDetails'] = $email[0];
                    header("Location: /home");
                    exit();
                } else {
                    $show_message = 'Incorrect login details. Please try again!';
                }
            } else {
                $show_message = 'Incorrect login details. Please try again!';
            }
        }
        return [
            'template' => 'login.html.php',
            'title' => 'Login',
            'variables' => ['show_message' => $show_message]
        ];
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

        if (!isset($_SESSION['userDetails']['checkAdmin'])) {
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
        header("Location: /login");
        exit();
    }
}
