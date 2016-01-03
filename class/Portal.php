<?php

namespace AppSync;

class Portal {
    protected $orgsync_id;
    protected $name;
    protected $umbrella_id;

    public function __construct($orgsync_id, $name, $umbrella_id)
    {
        $this->orgsync_id = $orgsync_id;
        $this->name = $name;
        $this->umbrella_id = $umbrella_id;
    }

    public function getOrgSyncId()
    {
        return $this->orgsync_id;
    }

    public function setOrgSyncId($orgsyncId)
    {
        $this->orgsync_id = $orgsyncId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUmbrellaId()
    {
        return $this->umbrella_id;
    }

    public function setUmbrellaId($umbrellaId)
    {
        $this->umbrella_id = $umbrellaId;
    }

}
