<?php

namespace AppSync;

use \Database;

class UmbrellaFactory {

    public static function getUmbrellas()
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM appsync_umbrella';

        $stmt = $db->prepare($query);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'AppSync\UmbrellaRestored');

        return $stmt->fetchAll();
    }

    public static function save($umbrella)
    {
    	$db = PdoFactory::getPdoInstance();

      $id = $umbrella->getId();

      if (isset($id)) {
        $query = "UPDATE appsync_umbrella SET (name, orgsync_id) = (:name, :orgsyncId) WHERE id = :id";

        $params = array(
                  'name' => $contract->getName(),
                  'orgsyncId' => $contract->getOrgSyncId()
      	);

      }else{
        // Insert
        $query = "INSERT INTO appsync_umbrella (id, name, orgsync_id) VALUES (nextval('appsync_umbrella_seq'), :name, :orgsyncId)";

        $params = array(
                  'name' => $contract->getName(),
                  'orgsyncId' => $contract->getOrgSyncId()
        );
      }

      $stmt = $db->prepare($query);
      $stmt->execute($params);

      // Update ID for a new object
      if (!isset($id)) {
        $umbrella->setId($db->lastInsertId('appsync_umbrella_seq'));
      }
    }
}
