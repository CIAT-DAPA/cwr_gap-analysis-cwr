<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/StorageBehaviorDAO.php';
require_once WORKSPACE_DIR . 'core/model/StorageBehavior.php';
class StorageBehaviorMySQL implements StorageBehaviorDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new StorageBehaviorMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getStorageBehavior($genus) {
        global $db;
        $query = "SELECT * FROM Storage_behaviour WHERE Genus = '" . $genus . "'";
        $result = $db->getRow($query);
        if (!empty($result)) {
            $storageBehavior = new StorageBehavior($result['ID']);
            $storageBehavior->setOrthodox(trim($result['Per_Orth']));
            $storageBehavior->setIntermeduate(trim($result['Per_Inter']));
            $storageBehavior->setRecalcitrant(trim($result['Per_Recalc']));
            $storageBehavior->setUnknown(trim($result['Per_Unknown']));
            $storageBehavior->setReference(trim($result['Ref']));
            return $storageBehavior;
        }
        return null;
    }

}

?>
