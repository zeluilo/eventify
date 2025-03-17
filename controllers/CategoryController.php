<?php

namespace Controllers;

session_start();
class CategoryController
{
    private $categoryTable;

    public function __construct($categoryTable)
    {
        $this->categoryTable = $categoryTable;
    }

    public function home(): array
    {
        return [
            'template' => 'home.html.php',
            'variables' => [''],
            'title' => 'Record Management System'
        ];
    }
}
