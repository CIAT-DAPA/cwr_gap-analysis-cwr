<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'config/db.php';
require_once WORKSPACE_DIR.'core/dao/CropBreedingUseDAO.php';
require_once WORKSPACE_DIR.'core/model/CropBreedingUse.php';
require_once WORKSPACE_DIR.'core/model/Taxon.php';
class CropBreedingUseMySQL implements CropBreedingUseDAO {
    private static $instance;
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new CropBreedingUseMySQL();
        }
        return self::$instance;
    }
    private function __construct() {}

    public function getCropBreedingUses($cropID) {
        global $db;
        $query = "select distinct b.ID, b.Pot_Conf, b.Description, ref.Ref, sp.*
                    from Breeding_data b
                    inner join species sp on b.Taxon_ID = sp.Taxon_ID
                    inner join Breeding_ref ref on b.Ref_ID = ref.ID
                    where b.`Crop_ID` = " . $cropID . " ORDER BY sp.Taxon_ID";

        $result = $db->getAll($query);
        $cropBreedingUses = array();
        foreach ($result as $r) {
            $cropBreedingUse = new CropBreedingUse($r["ID"]);
            $cropBreedingUse->setUseType(trim($r['Pot_Conf']));
            $cropBreedingUse->setDescription(trim($r['Description']));
            $cropBreedingUse->setReference(trim($r['Ref']));

            $taxon = new Taxon(trim($r['Taxon_ID']));
            $taxon->setCommonName(trim($r["Common_Name"]));
            $taxon->setGenus(trim($r["Genus"]));
            $taxon->setSpecie(trim($r["Species"]));
            $taxon->setSpecieAuthor(trim($r["Species_Author"]));
            $taxon->setSubspecie(trim($r["Subsp"]));
            $taxon->setSubspecieAuthor($r["Subsp_Author"]);
            $taxon->setVariety(trim($r["Var"]));
            $taxon->setVarietyAuthor(trim($r["Var_Author"]));
            $taxon->setForm(trim($r["Form"]));
            $taxon->setFormAuthor(trim($r["Form_Author"]));

            $cropBreedingUse->setTaxon($taxon);
            // insert reference to the array.
            array_push($cropBreedingUses, $cropBreedingUse);
        }
        return $cropBreedingUses;
    }

}

?>
