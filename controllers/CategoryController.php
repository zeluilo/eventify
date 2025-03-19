<?php

namespace Controllers;

// session_start();
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

    public function create(): array
    {
        $category = $this->categoryTable->findAll();
        $message = '';

        if (isset($_POST['submit'])) {
            $values = [
                'category_name' => $_POST['category_name'],
                'category_description' => $_POST['category_description'],
                'datecreate' => date('Y-m-d H:i')
            ];
            $inserted = $this->categoryTable->insert($values);

            if ($inserted) {
                $message = 'Failed to add category. Please try again.';
            } else {
                $message = 'Category added successfully!';
            }
        }

        return [
            'template' => 'category.php',
            'variables' => [
                'category' => $category,
                'message' => $message,
            ],
            'title' => 'Add Category',
        ];
    }

    public function update(): array
    {
        $this->checkLogin();
        $categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;
        $category = $this->categoryTable->find('categoryId', $categoryId)[0];

        // Fetch events for the current category
        $events = $this->eventTable->find('categoryId', $categoryId);

        $message = '';

        if (isset($_POST['submit'])) {
            $values = [
                'categoryId' => $_POST['categoryId'],
                'catname' => $_POST['catname'],
                'dateupdate' => date('Y-m-d H:i')
            ];

            $inserted = $this->categoryTable->update($values);

            if ($inserted) {
                $message = 'Failed to add category. Please try again.';
            } else {
                $message = 'Category updated successfully!';
            }

            header('location: /pages/manageCategory');
        }

        return [
            'template' => 'editCategory.php',
            'variables' => [
                'category' => $category,
                'events' => $events,
                'message' => $message
            ],
            'title' => 'Edit category'
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
