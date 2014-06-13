<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/UtilizationDAO.php';
require_once WORKSPACE_DIR . 'core/model/Utilization.php';
class UtilizationMySQL implements UtilizationDAO {
    
    private static $instance;
    
    private function __construct() {}
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new UtilizationMySQL();
        }
        return self::$instance;
    }

    
    public function getUtilizations($taxonID) {
        global $db;
        $query = "SELECT * FROM Utilisation WHERE Taxon_ID = " . $taxonID . " ORDER BY Util_Type";
        $results = $db->getAll($query);
        $utilizations = array();
        foreach ($results as $result) {
            $taxonUsage = new Utilization($result['ID']);
            $taxonUsage->setType(trim($result['Util_Type']));
            $taxonUsage->setUse(trim($result['Util_Use']));
            array_push($utilizations, $taxonUsage);
        }
        return $utilizations;
    }
}

?>
