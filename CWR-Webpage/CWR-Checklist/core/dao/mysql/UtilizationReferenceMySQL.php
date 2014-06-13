<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/UtilizationReferenceDAO.php';
require_once WORKSPACE_DIR . 'core/model/UtilizationReference.php';
class UtilizationReferenceMySQL implements UtilizationReferenceDAO {

    private static $instance;
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new UtilizationReferenceMySQL();
        }
        return self::$instance;
    }
    
    private function __construct() {}

    
    public function getUtilizationReferences($taxonID) {
        global $db;
        $query = "SELECT * FROM Utilisation_ref WHERE Taxon_ID = " . $taxonID . " ORDER BY Name";
        $results = $db->getAll($query);
        $utilizationReferences = array();
        foreach ($results as $result) {
            $taxonUsageReference = new UtilizationReference($result['ID']);
            $taxonUsageReference->setName(trim($result['Name']));
            $taxonUsageReference->setPublication(trim($result['Publication']));
            $taxonUsageReference->setPage(trim($result['Page']));
            $taxonUsageReference->setAuthor(trim($result['Author']));
            $taxonUsageReference->setYear(trim($result['Year']));
            $taxonUsageReference->setDescription(trim($result['Description']));
            $taxonUsageReference->setPath(trim($result['Path']));
            array_push($utilizationReferences, $taxonUsageReference);
        }
        return $utilizationReferences;
    }

}

?>
