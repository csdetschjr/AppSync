<?php

namespace AppSync\Command;

class AjaxGetUmbrellaList extends \AppSync\Command {

      public function getRequestVars(){
          return array('action'=>'AjaxGetUmbrellaList');
      }

      public function execute()
      {
        $umbrellasResult = \AppSync\UmbrellaFactory::getUmbrellas();

        $umbrellas = array();

        $i = 0;

        foreach($umbrellasResult as $umbrella)
        {
            $umbrellas[$i]['umbrella_id'] = $umbrella->getOrgSyncId();
            $umbrellas[$i]['umbrella_name'] = $umbrella->getName();
            $i++;
        }

        echo json_encode($umbrellas);
        exit;
      }
}
