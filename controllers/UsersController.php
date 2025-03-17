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
    
    public function home(): array
    {
        return [
            'template' => 'home.html.php',
            'variables' => [''],
            'title' => 'Record Management System'
        ];
    }
}