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

    public function home(): array
    {
        return [
            'template' => 'home.html.php',
            'variables' => [''],
            'title' => 'Record Management System'
        ];
    }
    public function save(): array
    {
        $message = '';
        if (isset($_POST['submit'])) {
            $category_name = $_POST['category_name'];
    
            // Check if the category is being edited or created
            $categoryId = isset($_POST['categoryId']) ? $_POST['categoryId'] : null;
            
            // Check if the category already exists
            $category_exist = $this->categoryTable->find('category_name', $category_name);
            
            if (!empty($category_exist) && (!$categoryId || $category_exist['category_id'] != $categoryId)) {
                $message = 'Category exists already';
                $_SESSION['errorMessage'] = $message;
                return [
                    'template' => 'category.php',
                    'variables' => [
                        'message' => $message,
                    ],
                    'title' => 'Save Category - Eventify',
                ];
            }
            
            $values = [
                'category_name' => $category_name,
            ];
            
            // If categoryId exists, it's an edit, else it's a new creation
            if ($categoryId) {
                // Update existing category
                $updated = $this->categoryTable->update($values, $categoryId);
    
                if ($updated) {
                    $_SESSION['categoryUpdateSuccess'] = true;
                    // header('Location: /events/view');
                    exit;
                } else {
                    $_SESSION['errorMessage'] = 'Failed to update category. Please try again.';
                }
            } else {
                // Create new category
                $values['datecreate'] = date('Y-m-d H:i');
                $inserted = $this->categoryTable->insert($values);
    
                if ($inserted) {
                    $_SESSION['categoryCreationSuccess'] = true;
                    header('Location: /category/save');
                    exit;
                } else {
                    $_SESSION['errorMessage'] = 'Failed to add category. Please try again.';
                }
            }
        }
        
        return [
            'template' => 'category.php',
            'variables' => [
                'message' => $message,
            ],
            'title' => 'Save Category - Eventify',
        ];
    }

    public function manage(): array
    {
        $this->checkLogin();
        $categories = $this->categoryTable->findAll();

        return [
            'template' => 'manageCategory.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'List Of Categories'
        ];
    }

    public function delete(): array
    {
        $this->checkLogin();
        if (isset($_GET['categoryId'])) {
            $id = $_GET['categoryId'];
            $this->categoryTable->delete($id);
            header('location: /pages/manageCategory');
        }
        return [
            'template' => 'manageCategory.html.php',
            'variables' => ['categories' => $this->categoryTable->findAll()],
            'title' => 'List Of Categories'
        ];
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
