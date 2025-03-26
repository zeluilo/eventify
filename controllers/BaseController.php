<?php

namespace Controllers;

class BaseController
{
    // Function to load a view
    protected function view($view, $data = [])
    {
        extract($data); // Convert array keys into variables
        $viewPath = __DIR__ . "/../views/$view.php";

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            $this->show404(); // If view not found, show 404
        }
    }

    // Function to redirect users
    protected function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    // Function to render content with an additional argument (e.g., for a title)
    protected function render($template, $data = [], $title = null)
    {
        // Optionally handle the title or pass it to the view
        if ($title) {
            // Add title to data array or use it elsewhere
            $data['title'] = $title;
        }

        try {
            $this->view($template, $data); // Use the view method to render the content
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }


    // Handle errors by logging and showing a generic error page
    protected function handleError(\Exception $exception) // Use global Exception class
    {
        // Log the error message
        error_log($exception->getMessage());

        // Store the error message in the session to be displayed in error_message.php
        $_SESSION['errorMessage'] = $exception->getMessage();

        // Include the error message script (to display the SweetAlert)
        include __DIR__ . "/../includes/error_message.php";

        // Show a generic error page
        header("HTTP/1.1 500 Internal Server Error");
        include __DIR__ . "/../views/error.php";
        exit();
    }

    // Check if user is logged in
    protected function isAuthenticated()
    {
        return isset($_SESSION['userDetails']);
    }

    // Check if user is an admin
    protected function isAdmin()
    {
        return isset($_SESSION['userDetails']) && $_SESSION['userDetails']['role'] === 'admin';
    }

    // Show 404 Page
    protected function show404()
    {
        header("HTTP/1.0 404 Not Found");
        include __DIR__ . "/../views/404.php";
        exit();
    }

    // Start a session if not already started
    protected function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Check if the user is logged in; if not, redirect to login
    protected function checkLogin()
    {
        $this->startSession();

        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
    }

    // Ensure the user is an admin; if not, redirect to user home
    protected function checkAdmin()
    {
        $this->startSession();

        $this->checkLogin();

        if (!$this->isAdmin()) {
            $this->redirectToUserHome();
        }
    }

    // Logout user and destroy session securely
    protected function logout()
    {
        $this->startSession();

        $_SESSION = [];
        session_unset();
        session_destroy();

        session_regenerate_id(true);

        // Expire session cookie
        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        $this->redirectToLogin();
    }

    // Redirect user to login page
    protected function redirectToLogin()
    {
        $this->redirect("/users/login");
    }

    // Redirect user to their home page
    protected function redirectToUserHome()
    {
        $this->redirect("/users/home");
    }
}
