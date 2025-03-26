<?php

namespace Controllers;

class CategoryController extends BaseController
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
        $this->checkAdmin();

        $category = $this->categoryTable->findAll();
        $message = '';

        $categoryId = $_POST['categoryId'] ?? ($_GET['categoryId'] ?? null);
        $isUpdate = !empty($categoryId);
        $existingCategory = $isUpdate ? $this->categoryTable->find('categoryId', $categoryId)[0] : null;

        if (isset($_POST['submit'])) {
            $category_name = $_POST['category_name'];

            // Check if category already exists
            $categoryExist = $this->categoryTable->find('category_name', $category_name);

            if (!empty($categoryExist) && (!$categoryId || $categoryExist['category_id'] != $categoryId)) {
                $_SESSION['errorMessage'] = 'Category already exists';
                return $this->viewResponse('category.php', [
                    'message' => 'Category already exists'
                ], 'Save Category - Eventify');
            }

            $values = ['category_name' => $category_name];

            if ($isUpdate) {
                $values['categoryId'] = $categoryId;
                $values['dateupdate'] = date('Y-m-d H:i');
                $updated = $this->categoryTable->update($values);

                if (!$updated) {
                    $_SESSION['categoryUpdateSuccess'] = true;
                    $this->redirect('/events/dashboard');
                } else {
                    $_SESSION['errorMessage'] = 'Failed to update category. Please try again.';
                }
            } else {
                $values['datecreate'] = date('Y-m-d H:i');
                $inserted = $this->categoryTable->insert($values);

                if (!$inserted) {
                    $_SESSION['categoryCreationSuccess'] = true;
                    $this->redirect('/events/dashboard');
                } else {
                    $_SESSION['errorMessage'] = 'Failed to add category. Please try again.';
                }
            }
        }

        return $this->viewResponse('category.php', [
            'category' => $isUpdate ? [$existingCategory] : null,
            'message' => $message
        ], $isUpdate ? 'Edit Category - Eventify' : 'Create Category - Eventify');
    }

    public function delete()
    {
        $this->checkAdmin();

        if (isset($_GET['categoryId'])) {
            $categoryId = $_GET['categoryId'];
            $category = $this->categoryTable->find('categoryId', $categoryId);

            if ($category) {
                $events = $this->eventTable->find('categoryId', $categoryId);
                if ($events) {
                    foreach ($events as $event) {
                        $this->eventTable->delete($event['eventId']);
                    }
                }

                $this->categoryTable->delete($categoryId);
                $_SESSION['categoryDeletionSuccess'] = true;
                $this->redirect('/events/dashboard');
            } else {
                $_SESSION['errorMessage'] = "Category not found.";
                $this->redirect('/events/dashboard');
            }
        }

        $this->redirect('/events/dashboard');
    }

    private function viewResponse($template, $variables, $title)
    {
        return [
            'template' => $template,
            'variables' => $variables,
            'title' => $title
        ];
    }
}
