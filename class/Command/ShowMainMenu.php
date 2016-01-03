<?php

namespace AppSync\Command;

class ShowMainMenu extends \AppSync\Command {


    public function getRequestVars(){
        return array('action'=>'ShowMainMenu');
    }

    public function execute()
    {
        $view = new \AppSync\UI\TopUI();
        $this->display($view->display());
    }
}
