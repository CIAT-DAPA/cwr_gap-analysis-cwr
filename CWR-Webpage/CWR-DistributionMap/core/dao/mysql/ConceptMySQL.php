<?php

/**
 *
 * @author Alex Gabriel Castaneda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/ConceptDAO.php';

class ConceptMySQL implements ConceptDAO {
    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ConceptMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }
    
    /**
     * Get array of types (string)
     * @global type $db
     * @return array 
     */
    public function getAllConceptTypes(){
        global $db;
        $query = "select Concept_Type from concepts group by Concept_Type;";
        $result = $db->getAll($query);
        $concept_type = array();

        foreach ($result as $r) {
            array_push($concept_type, $r["Concept_Type"]); // add value to array_test
        }

        return $concept_type;
    }
    
    /**
     * get all levels by concept type
     * @global type $db
     * @param type $type
     * @return array 
     */
    public function getAllLevelsByType($type){
        global $db;
        $query = "select Concept_Level from concepts where Concept_Type = '".$type."' group by Concept_Level";
        $result = $db->getAll($query);
        $concept_level = array();
        
        foreach ($result as $r) {
            array_push($concept_level,$r["Concept_Level"]);
        }
        
        return $concept_level;
    }
}

?>
