<?php

namespace AppSync\Command;


/**
* Controller class for getting portal search suggestion data in JSON format.
*
* @author Chris Detsch
* @package intern
*/
class AjaxGetPortalMembers {

    public function __construct()
    {

    }

    public function execute()
    {
        try
        {
            $portalMembers = $this->getOrgMembers($_REQUEST['org_id']);

            echo json_encode($portalMembers);

        }catch(Exception $e)
        {
            echo '<div style="display: none;">'.$e->getMessage().'</div>';
        }
        exit;
    }



    function getOrgMembers($org_id){
        // This will need to be moved to the settings.
        $key = 'jKyMOiAVkKxSX5IWy-B6rNna5IW6qGT3YzGm3unyR0A';
        $base_url = 'https://sandbox.orgsync.com/api/v2/';
        $curl = curl_init();
        //get organization members by organization id
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $base_url."orgs/$org_id/accounts?key=$key"));
        $org_members = curl_exec($curl);
        if($org_members){
            $org_members = json_decode($org_members);
        }else{
            $org_members = FALSE;
        }
        curl_close($curl);
        return $org_members;
    }
}
