<?php

namespace Controllers;

class UsersController extends BaseController
{
    private $userTable;
    private $eventTable;

    public function __construct($userTable, $eventTable)
    {
        parent::__construct();
        $this->userTable = $userTable;
        $this->eventTable = $eventTable;
    }

    public function home()
    {
        $this->checkLogin(); 
    
        $registrationSuccess = false;
        $loggedinSuccess = false;
        $loggedoutSuccess = isset($_SESSION['loggedout']) ? $_SESSION['loggedout'] : false;
    
        if ($loggedoutSuccess) {
            unset($_SESSION['loggedout']);
        }
    
        return $this->render('home.php', [
            'registrationSuccess' => $registrationSuccess,
            'loggedinSuccess' => $loggedinSuccess,
            'loggedoutSuccess' => $loggedoutSuccess,
        ], 'Eventify - Discover Events');
    }

    public function save(): array
    {
        $message = '';
        $userId = $_POST['userId'] ?? ($_GET['userId'] ?? null);
        $isUpdate = !empty($userId);
        $existingUser = $isUpdate ? $this->userTable->find('userId', $userId)[0] : null;

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $existingEmail = $this->userTable->find('email', $email);

            if (!empty($existingEmail) && (!$isUpdate || $existingEmail[0]['userId'] !== $userId)) {
                return $this->handleError('Account exists already', 'register.php');
            }

            $passwordHash = $this->handlePassword($userId, $existingUser);
            if (!$passwordHash) return $this->handleError($_SESSION['errorMessage'], 'register.php');

            $uuId = empty($existingUser['secure_id']) ? bin2hex(random_bytes(16)) : $existingUser['uuId'];

            $values = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $passwordHash,
                'phone' => $_POST['phone'],
                'uuId' => $uuId,
                'profile_pic' => $this->handleProfilePicture($existingUser['profile_pic'] ?? null),
            ];

            return $isUpdate ? $this->updateUser($userId, $values) : $this->createUser($values);
        }
    }

    private function handlePassword($userId, $existingUser)
    {
        $password = $_POST['password'] ?? '';
        $repeatPassword = $_POST['repeat_password'] ?? '';

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
        if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['name'] === "") {
            return $existingProfilePic;
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        $uploadDir = "images/profile_pics/";

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

        $targetPath = $uploadDir . basename($_FILES['profile_pic']['name']);
        $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions) || !getimagesize($_FILES["profile_pic"]["tmp_name"])) {
            $_SESSION['errorMessage'] = 'Invalid file format or not a valid image.';
            return false;
        }

        return move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath) ? $_FILES["profile_pic"]["name"] : $existingProfilePic;
    }

    private function updateUser($userId, $values)
    {
        $values['userId'] = $userId;
        $values['dateupdated'] = date('Y-m-d H:i');
        $updated = $this->userTable->update($values);

        if (!$updated) {
            $_SESSION['userUpdateSuccess'] = true;
            header("Location: /users/view?userId=$userId");
            exit;
        }
        return $this->handleError('Failed to update user. Please try again.', 'register.php');
    }

    private function createUser($values)
    {
        $values['datecreated'] = date('Y-m-d H:i');
        $values['user_role'] = 'USER';
        $inserted = $this->userTable->insert($values);

        if (!$inserted) {
            $_SESSION['userCreationSuccess'] = true;
            header(isset($_SESSION['userDetails']) ? 'Location: /events/dashboard' : 'Location: /users/save');
            exit;
        }
        return $this->handleError('Failed to create user. Please try again.', 'register.php');
    }
}
