<?php

namespace AppSync\Command;


/**
 * Controller class for getting portal search suggestion data in JSON format.
 *
 * @author Chris Detsch
 * @package intern
 */
class GetSearchSuggestions {

    const tokenLimit        = 2;
    const fuzzyTolerance    = 3;
    const resultLimit       = 10;

    public function __construct()
    {

    }

    public function execute()
    {
        try
        {
            $portals = \AppSync\PortalFactory::getPortals();

            $searchString = $_REQUEST['searchString'];
            $umbrella = $_REQUEST['umbrellaId'];

            $portList = $this->portalFuzzySearch($searchString, $umbrella, $portals);
            echo $this->encodePortals($portList);
        }catch(Exception $e)
        {
            echo '<div style="display: none;">'.$e->getMessage().'</div>';
        }
        exit;
    }


    private function portalFuzzySearch($string, $umbrellaId, $orgList)
    {
        $portList = array();
        foreach ($orgList as $org) {
            if($org->getUmbrellaId() == $umbrellaId)
            {
                $name = strtolower($org->getName());
                if(strpos($name, $string) !== false)
                {
                    array_push($portList, $org);
                }
            }
        }
        return $portList;
    }


    /**
     * Takes an array of Student objects and encodes them into a
     * json_encoded string.
     */
    private function encodePortals($portals) {
        $portalsEncoded = array();

        $i = 0;
        foreach($portals as $portal) {
            $portalsEncoded[$i]['name'] = $portal->getName();
            $portalsEncoded[$i]['id'] = $portal->getOrgSyncId();
            $i++;
        }

        return json_encode($portalsEncoded);
    }

    /**
     * Attempts to find a student by their student ID. Throws an exception if the student
     * cannot be located.
     *
     * @param $studentId The student's ID number.
     * @throws StudentNotFoundException
     */
    private function studentIdSearch($studentId)
    {
        $student = StudentProviderFactory::getProvider()->getStudent($studentId, Term::timeToTerm(time()));

        return $student;
    }

}
