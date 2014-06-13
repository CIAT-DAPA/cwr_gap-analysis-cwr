<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/BreedingUseDAO.php';
require_once WORKSPACE_DIR . 'core/model/BreedingUse.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

class BreedingUseMySQL implements BreedingUseDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new BreedingUseMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getCropBreedingUses($cropID) {
        global $db;
        /*
          $query = "select DISTINCT b.ID, b.Pot_Conf, b.Description, ref.Ref, sp2.*
          from Breeding_data b
          inner join concepts c on c.Crop_ID = b.Crop_ID
          inner join species sp on b.Taxon_ID = sp.Taxon_ID
          inner join Breeding_ref ref on b.Ref_ID = ref.ID
          INNER JOIN species sp2 ON sp.Valid_Taxon_ID = sp2.Taxon_ID
          where c.Taxon_ID = " . $cropID . "
          ORDER BY b.Pot_Conf, sp2.Genus, sp2.Species, sp2.Species_Author, sp2.Subsp, sp2.Subsp_Author, sp2.Var, sp2.Var_Author,
          sp2.Form, sp2.Form_Author"; */

        $query = "SELECT DISTINCT b.ID, b.Pot_Conf, b.Description, ref.Ref, sp2 . * 
                    FROM Breeding_data b
                    INNER JOIN concepts c ON c.Crop_ID = b.Crop_ID
                    INNER JOIN species sp ON b.Taxon_ID = sp.Taxon_ID
                    INNER JOIN Breeding_ref ref ON b.Ref_ID = ref.ID
                    INNER JOIN species sp2 ON sp.Valid_Taxon_ID = sp2.Taxon_ID
                    WHERE c.Crop_ID IN ( 
                    SELECT Crop_ID
                    FROM concepts
                    WHERE Taxon_ID = $cropID
                    AND CWR_Flag = 0 ) 
                    ORDER BY b.Pot_Conf, sp2.Genus, sp2.Species, sp2.Species_Author, sp2.Subsp, sp2.Subsp_Author, sp2.Var, sp2.Var_Author, sp2.Form, sp2.Form_Author";

        
         $result = $db->getAll($query);
         $cropBreedingUses = array();
         $test_array = array();

         if($result != null){
            foreach ($result as $r) {
                $cropBreedingUse = new BreedingUse($r["ID"]);
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
                $taxon->setScientificName(trim($r["Scientific_Name"]));

                $cropBreedingUse->setTaxon($taxon);
                // insert reference to the array.
                array_push($cropBreedingUses, $cropBreedingUse);

                // Join every except ID value as String
                $value = trim($r['Pot_Conf']) . trim($r['Description']) . trim($r['Ref']) . trim($r['Taxon_ID']) .
                        trim($r["Common_Name"]) . trim($r["Genus"]) . trim($r["Species"]) . trim($r["Species_Author"]) .
                        trim($r["Subsp"]) . $r["Subsp_Author"] . trim($r["Var"]) . trim($r["Var_Author"]) . trim($r["Form"])
                        . trim($r["Form_Author"]) . trim($r["Scientific_Name"]);
                array_push($test_array, $value); // add value to array_test
            }

            $index_array = $this->getIndexDup($test_array);
            for ($i = 0; $i < count($index_array); $i++) {// Extract the duplicate elements
                unset($cropBreedingUses[$index_array[$i] - $i]);
            }

            return $cropBreedingUses;
        
         }else{
             return null;
         }
        
    }

    /*
     * Extrae en un arreglo los indices donde ha encontrado una repeticion del registro, luego se debe proceder
     * a borrar dichos registros. (del arreglo final no de la base de datos)
     */

    public function getIndexDup($array) {
        $index_array = array();
        $final_array = array();

        for ($i = 0; $i < count($array); $i++) {
            if (count($final_array) != 0) {// not empty
                $index = array_search($array[$i], $final_array);

                if ($index) {
                    if ($index >= 0) {
                        array_push($index_array, $index);
                    }
                }

                array_push($final_array, $array[$i]);
            } else {
                array_push($final_array, $array[$i]);
            }
        }

        return $index_array;
    }

    public function getTaxonBreedingUses($taxonID) {
        global $db;

        $query = "select b.ID, b.Pot_Conf, b.Description, ref.Ref, sp.*
                    from Breeding_data b
                    inner join species sp on b.Taxon_ID = sp.Taxon_ID
                    inner join Breeding_ref ref on b.Ref_ID = ref.ID
                    where b.Taxon_ID = " . $taxonID . " ORDER BY sp.Taxon_ID";
        $result = $db->getAll($query);
        $taxonBreedingUses = array();
        foreach ($result as $r) {
            $taxonBreedingUse = new BreedingUse($r["ID"]);
            $taxonBreedingUse->setUseType(trim($r['Pot_Conf']));
            $taxonBreedingUse->setDescription(trim($r['Description']));
            $taxonBreedingUse->setReference(trim($r['Ref']));

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

            $taxonBreedingUse->setTaxon($taxon);
            // insert reference to the array.
            array_push($taxonBreedingUses, $taxonBreedingUse);
        }
        return $taxonBreedingUses;
    }

    public function getAllBreedingUses() {
        global $db;
        $query = "SELECT DISTINCT(Description) as Description FROM Breeding_data";
        $result = $db->getAll($query);
        $uses = array();
        foreach ($result as $r) {
            $description = trim($r["Description"]);
            foreach (split(", ", $description) as $use) {
                array_push($uses, ucfirst(strtolower($use)));
            }
        }
        $uses = array_unique($uses);
        sort($uses, SORT_STRING);
        return $uses;
    }

}

?>
