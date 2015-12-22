<?php

namespace AppSync\Command;

class AjaxUpdateOrgData extends \AppSync\Command {

      public function getRequestVars(){
          return array('action'=>'AjaxGetUmbrellaList');
      }

      public function execute()
      {
        //   $orgs = $this->getAllOrganizations();

          foreach ($orgs as $org) {
              $orgId = $org->id;
              $name = $org->long_name;
              $umbrellaId = $org->umbrella_id;
              $portal = new \AppSync\Portal($orgId, $name, $umbrellaId);
              \AppSync\PortalFactory::save($portal);
          }

      }

      private function getAllOrganizations(){
          // This will need to be moved to the settings.
          $key = 'jKyMOiAVkKxSX5IWy-B6rNna5IW6qGT3YzGm3unyR0A';
          $base_url = 'https://sandbox.orgsync.com/api/v2/';

          $curl = curl_init();
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          //Request list of all orginizations
          curl_setopt($curl, CURLOPT_URL, $base_url."orgs?key=$key");
          $all_org = curl_exec($curl);
          if($all_org){
              $all_org = json_decode($all_org);
          }else{
              $all_org = FALSE;
          }
          curl_close($curl);
          return $all_org;
      }
}
