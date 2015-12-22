<?php

namespace AppSync;

class Umbrella {

    protected $id;
    protected $name;
    protected $orgsync_id;

    // TODO: make first parameter an instance of $student
    public function __construct($id, $name, $orgsync_id)
    {
    	$this->id = $id;
        $this->name = $name;
        $this->orgsync_id = $orgsync_id;
    }

    public function getId()
    {
    	return $this->id;
    }

    public function setId($id)
    {
    	$this->id = $id;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName($name)
    {
      $this->name = $name;
    }

    public function getOrgSyncId()
    {
    	return $this->orgsync_id;
    }

    public function setOrgSyncId($orgsyncId)
    {
      $this->orgsync_id = $orgsyncId;
    }
}
