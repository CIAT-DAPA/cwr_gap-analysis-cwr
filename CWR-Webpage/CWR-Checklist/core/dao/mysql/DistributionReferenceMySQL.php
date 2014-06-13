<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/DistributionReferenceDAO.php';
require_once WORKSPACE_DIR . 'core/model/DistributionReference.php';

class DistributionReferenceMySQL implements DistributionReferenceDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DistributionReferenceMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getDistributionReferences($taxonID) {
        global $db;
        $query = "SELECT * FROM distribution_ref WHERE Taxon_ID = " . $taxonID . " ORDER BY Ref";        
        
        $results = $db->getall($query);        
        $references = array();        
       foreach ($results as $result) {
            $reference = new DistributionReference($result['ID']);
            $reference->setReference(trim($result['Ref']));
            $reference->setWebpage(trim($result['Webpage']));
            array_push($references, $reference);
        }
        return $references;
    }

}

?>
