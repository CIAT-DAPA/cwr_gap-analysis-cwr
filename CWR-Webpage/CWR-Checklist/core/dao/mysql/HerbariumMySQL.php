<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'config/db.php';
require_once WORKSPACE_DIR.'core/dao/HerbariumDAO.php';
require_once WORKSPACE_DIR.'core/model/Herbarium.php';
class HerbariumMySQL implements HerbariumDAO {
    private static $instance;
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new HerbariumMySQL();
        }
        return self::$instance;
    }
    
    private function __construct() {}
    
    /**
     *
     * @param type $db
     * @param type $TaxonID
     */
    public function getHerbariaData($taxonID) {
        global $db;
        $query = "SELECT * FROM Herbaria_data hd
            INNER JOIN Herbaria_lookup hlook ON hd.Code = hlook.Inst_Code
            WHERE hd.Taxon_ID = " . $taxonID . " GROUP BY hlook.Inst_Code";
        $result = $db->getAll($query);
        $herbaria = array();
        foreach ($result as $r) {
            $herbarium = new Herbarium($r["ID"]);
            $herbarium->setCountryCode(trim($r['Country']));
            $herbarium->setInstitutionCode(trim($r['Inst_Code']));
            $herbarium->setInstitutionName(trim($r['Inst_Name']));
            $herbarium->setInstitutionLocation(trim($r['Inst_Location']));
            // insert herbarium to the array.
            array_push($herbaria, $herbarium);
        }
        return $herbaria;
    }
}

?>
