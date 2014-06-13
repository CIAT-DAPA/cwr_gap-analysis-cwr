<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'config/db.php';
require_once WORKSPACE_DIR.'core/dao/ClassificationReferenceDAO.php';
require_once WORKSPACE_DIR.'core/model/ClassificationReference.php';
class ClassificationReferenceMySQL implements ClassificationReferenceDAO {
    private static $instance;
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new ClassificationReferenceMySQL();
        }
        return self::$instance;
    }
    
    private function __construct() {}
   /**
     *
     * @param type $db - ADONewConnection
     * @param type $cropID - Crop ID to search from concepts table.
     * @return array - Array of ClassificationReference objects.
     */
    public function getClassificationReferences($cropID) {
        global $db;
        $query = "SELECT * FROM Classification_ref WHERE  Taxon_ID = " . $cropID . " ORDER BY Ref";
        $result = $db->getAll($query);
        $classificationReferences = array();
        foreach ($result as $r) {
            $classificationRef = new ClassificationReference($r["Taxon_ID"]);
            $classificationRef->setReference($r["Ref"]);

            // insert classification to the array.
            array_push($classificationReferences, $classificationRef);
        }
        return $classificationReferences;
    }
}

?>
