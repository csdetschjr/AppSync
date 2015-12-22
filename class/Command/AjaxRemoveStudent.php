<?php

namespace AppSync\Command;

class AjaxRemoveStudent extends \AppSync\Command {

    public function getRequestVars(){
        return array('action'=>'AjaxRemoveStudent');
    }

    public function execute()
    {
        $input = $_REQUEST['inputData'];
        $portal = $_REQUEST['portalId'];

        if(!is_numeric($input))
        {
            //Banner
            $banner = $this->getBannerIDFromEmail($input);
            if($banner === false)
            {
                echo json_encode(array('status' => 'error', 'message' => 'Email/Username was invalid'));
                exit;
            }
        }
        else {
            $banner = $input;
        }

        $student = $this->getStudentByBanner($banner);

        if($student === false)
        {
            $returnData = array('status' => 0);
            echo json_encode($returnData);
            exit;
        }

        $status = $this->removeAccount($student->{'emailAddress'}, $portal);

        $name = $student->{'firstName'} . ' ' . $student->{'lastName'};
        if($status)
        {
            $returnData = array('status' => 1, 'name' => $name);
        }
        else
        {
            $returnData = array('status' => 2, 'name' => $name);
        }

        echo json_encode($returnData);
        exit;
    }


    function getBannerIDFromEmail($email){
        $parts = explode("@", $email);
        $username = strtolower($parts[0]);
        if(!empty($username)){
            $query = "SELECT * FROM sdr_member WHERE username='$username' ORDER BY id DESC";
            $result = pg_query($query);
            if($result && pg_num_rows($result) > 0){
                $row = pg_fetch_assoc($result);
                return $row['id'];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }


    /**
    * Remove an account or multiple accounts from an organization. $ids can be one id or and array of ids.
    *
    *
    */
    function removeAccount($user_id, $org_id){
        $key = 'jKyMOiAVkKxSX5IWy-B6rNna5IW6qGT3YzGm3unyR0A';
        $base_url = 'https://sandbox.orgsync.com/api/v2/';
        $id = $this->getIDFromUsername($user_id);

        $url = $base_url."/orgs/$org_id/accounts/remove";
        $curl = curl_init();
        curl_setopt_array($curl, array(CURLOPT_TIMEOUT => 900, CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $url, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => "ids=$id&key=$key"));
        $result = curl_exec($curl);
        curl_close($curl);
        if($result){
            $result = json_decode($result);
            if(is_object($result) && $result->success == "true")
            return TRUE;
            else
            return FALSE;
        }
    }


    function getIDFromUsername($username){
        $key = 'jKyMOiAVkKxSX5IWy-B6rNna5IW6qGT3YzGm3unyR0A';
        $base_url = 'https://sandbox.orgsync.com/api/v2/';
        $curl = curl_init();
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $base_url."accounts/username/$username?key=$key"));
        $result = curl_exec($curl);
        curl_close($curl);
        if($result){
            $result = json_decode($result);
            if(!empty($result->id))
            return $result->id;
        }
        return false;
    }


    function getStudentByBanner($banner)
    {
        $base_url = 'http://bansrvtest.its.appstate.edu:8086/api/';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, $base_url."Student");
        $studentList = json_decode(curl_exec($curl));

        foreach ($studentList as $student) {
            if($student->{'ID'} == $banner)
            {
                return $student;
            }
        }

        return false;
    }

}
