<?php

namespace Controllers;

class CategoryController
{
    private $categoryTable;
    private $eventTable;


    public function __construct($categoryTable, $eventTable)
    {
        $this->categoryTable = $categoryTable;
        $this->eventTable = $eventTable;
    }
    public function save(): array
    {
        $category = $this->categoryTable->findAll();
        $message = '';
        
        $categoryId = $_POST['categoryId'] ?? ($_GET['categoryId'] ?? null);
        $isUpdate = !empty($categoryId);
        $existingCategory = $isUpdate ? $this->categoryTable->find('categoryId', $categoryId)[0] : null;
    
        // Handle form submission
        if (isset($_POST['submit'])) {
            $category_name = $_POST['category_name'];
    
            // Check if category already exists
            $categoryExist = $this->categoryTable->find('category_name', $category_name);
    
            if (!empty($categoryExist) && (!$categoryId || $categoryExist['category_id'] != $categoryId)) {
                $message = 'Category already exists';
                $_SESSION['errorMessage'] = $message;
                return [
                    'template' => 'category.php',
                    'variables' => [
                        'message' => $message,
                    ],
                    'title' => 'Save Category - Eventify',
                ];
            }
    
            // Prepare category data for insertion or update
            $values = [
                'category_name' => $category_name,
            ];
    
            // If categoryId exists, it's an update, else it's a new creation
            if ($isUpdate) {
                // Update existing category
                $values['categoryId'] = $categoryId;
                $values['dateupdate'] = date('Y-m-d H:i');
                $updated = $this->categoryTable->update($values);
    
                if (!$updated) {
                    $_SESSION['categoryUpdateSuccess'] = true;
                    header('Location: /events/dashboard');
                    exit;
                } else {
                    $message = 'Failed to update category. Please try again.';
                    $_SESSION['errorMessage'] = $message;
                }
            } else {
                // Create new category
                $values['datecreate'] = date('Y-m-d H:i');
                $inserted = $this->categoryTable->insert($values);
    
                if (!$inserted) {
                    $_SESSION['categoryCreationSuccess'] = true;
                    header('Location: /events/dashboard');
                    exit;
                } else {
                    $message = 'Failed to add category. Please try again.';
                    $_SESSION['errorMessage'] = $message;
                }
            }
        }
    
        $category = $isUpdate ? [$existingCategory] : null;
    
        return [
            'template' => 'category.php',
            'variables' => [
                'category' => $category,
                'message' => $message,
            ],
            'title' => $isUpdate ? 'Edit Category - Eventify' : 'Create Category - Eventify',
        ];
    }
    public function delete(): array
    {
        $message = '';
    
        // Check if categoryId is provided in the request
        if (isset($_GET['categoryId'])) {
            $categoryId = $_GET['categoryId'];
    
            // Find the category by categoryId
            $category = $this->categoryTable->find('categoryId', $categoryId);
    
            if ($category) {    
                // Delete events associated with the category first
                $events = $this->eventTable->find('categoryId', $categoryId);
                if ($events) {
                    foreach ($events as $event) {
                        $this->eventTable->delete($event['eventId']);
                    }
                } else {
                    error_log("No events found for category ID: " . $categoryId);
                }
    
                // Delete the category
                $this->categoryTable->delete($categoryId);
                $_SESSION['categoryDeletionSuccess'] = true;
    
                // Redirect to the events dashboard
                header('Location: /events/dashboard');
                exit();
            } else {
                $_SESSION['errorMessage'] = "Category not found.";
                header('Location: /events/dashboard');
                exit();
            }
        }
    
        header('Location: /events/dashboard');
        exit();
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
