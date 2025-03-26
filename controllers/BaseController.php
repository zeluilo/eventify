<?php

namespace Controllers;

class BaseController
{
    // Function to redirect users
    protected function redirect($url)
    {
        header("Location: $url");
        exit();
    }
    // Handle errors by logging and showing a generic error page
    public function handleError(string $message): void
    {
        $_SESSION['errorMessage'] = $message;
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
        return isset($_SESSION['userDetails']) && $_SESSION['userDetails']['user_role'] === 'ADMIN';
    }

    // Show 404 Page
    protected function show404()
    {
        header("HTTP/1.0 404 Not Found");
        include __DIR__ . "/../includes/404.php";
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
