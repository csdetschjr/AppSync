<?php

namespace AppSync;

class AppSync {

    private $content;

    public function __construct()
    {

    }

    public function getContent()
    {
        return $this->content;
    }

    public function handleRequest()
    {
        // Fetch the action from the REQUEST.
        if (!isset($_REQUEST['action'])) {
            $action = 'ShowMainMenu';
        } else {
            $action = $_REQUEST['action'];
        }

        $action = 'AppSync\Command\\' . $action;
        $ctrl = new $action();
        $this->content = $ctrl->execute($this);

    }

}

?>
