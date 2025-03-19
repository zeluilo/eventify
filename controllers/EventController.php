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
    
    public function createEvent (): array
    {
        return [
            'template' => 'events.php',
            'variables' => [''],
            'title' => 'Record Management System'
        ];
    }
}