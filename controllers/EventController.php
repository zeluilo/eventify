<?php
namespace Controllers;

// session_start();
class EventController
{
    private $eventTable;

    public function __construct($eventTable)
    {
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
}