<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'config/db.php';
require_once WORKSPACE_DIR.'core/dao/ConceptReferenceDAO.php';
require_once WORKSPACE_DIR.'core/model/ConceptReference.php';
class ConceptReferenceMySQL implements ConceptReferenceDAO {
    private static $instance;
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new ConceptReferenceMySQL();
        }
        return self::$instance;
    }
    private function __construct() {}
   /**
     *
     * @param type $db - ADONewConnection
     * @param type $conceptID - Concept ID to search from concepts_ref table.
     * @return type array - Array of ConceptReference objects.
     */
    public function getConceptReferences($conceptID) {
        global $db;
        $query = "SELECT * FROM concepts_ref WHERE Concept_ID = " . $conceptID;
        $result = $db->getAll($query);
        $conceptReferences = array();
        foreach ($result as $r) {
            $conceptRef = new ConceptReference($r["ID"]);
            $conceptRef->setConceptID($conceptID);
            $conceptRef->setWebPage(trim($r["Webpage"]));
            $conceptRef->setReference(trim($r["Ref"]));
            // insert reference to the array.
            array_push($conceptReferences, $conceptRef);
        }
        return $conceptReferences;
    }
}

?>
