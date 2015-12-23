<?php

namespace AppSync;

use \Database;

class SettingFactory {

    public static function getOrgSyncKey()
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM appsync_umbrella WHERE setting = orgsync_key';

        $stmt = $db->prepare($query);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'AppSync\SettingRestored');

        return $stmt->fetchAll();
    }
}
