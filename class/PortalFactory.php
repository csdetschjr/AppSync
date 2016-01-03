<?php

namespace AppSync;

use \Database;

class PortalFactory {

    public static function getPortals()
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM appsync_portal';

        $stmt = $db->prepare($query);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'AppSync\PortalRestored');

        return $stmt->fetchAll();
    }



    public static function getPortalById($orgsyncId)
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM appsync_portal WHERE orgsync_id = :orgsyncId';

        $stmt = $db->prepare($query);

        $params = array(
            'orgsyncId' => $orgsyncId
        );

        $stmt->execute($params);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'AppSync\PortalRestored');

        return $stmt->fetchAll();
    }

    public static function getPortalByName($name)
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM appsync_portal WHERE name = :name';

        $stmt = $db->prepare($query);

        $params = array(
            'name' => $name
        );

        $stmt->execute($params);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'AppSync\PortalRestored');

        return $stmt->fetchAll();
    }

    public static function save($portal)
    {
        $db = PdoFactory::getPdoInstance();

        $orgsync_id = $portal->getOrgSyncId();

        if (!empty(self::getPortalById($orgsync_id))) {
            $query = "UPDATE appsync_portal SET (name, umbrella_id) = (:name, :umbrellaId) WHERE orgsync_id = :orgsyncId";

            $params = array(
                'name' => $portal->getName(),
                'umbrellaId' => $portal->getUmbrellaId()
            );
        }else{
            // Insert
            $query = "INSERT INTO appsync_portal (orgsync_id, name, umbrella_id) VALUES (:orgsyncId, :name, :umbrellaId)";

            $params = array(
                'name' => $portal->getName(),
                'orgsyncId' => $portal->getOrgSyncId(),
                'umbrellaId' => $portal->getUmbrellaId()
            );
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }
}
