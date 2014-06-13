<?php

/**
 *
 * @author  Alex G. Castañeda V
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/TaxonDAO.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

// TODO Delete this because is should be called in other part.
require_once WORKSPACE_DIR . 'core/util/ObjectArrayUtil.php';

// TODO Delete also the firePHP.

class TaxonMySQL implements TaxonDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new TaxonMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    /**
     * Retorna el ID del crop del cual es sinonimo en caso de serlo, caso contrario simplemente
     * devolvera un array vacio
     * @param type $taxonID 
     */
    public function isSynonym($taxonID) {
        global $db;
        $query = "(SELECT Valid_Taxon_ID FROM species WHERE Taxon_ID = $taxonID AND Taxon_ID IN(SELECT Taxon_ID FROM species WHERE Valid_Taxon_ID != Taxon_ID))";
        $result = $db->getAll($query);
        $id = array();
        foreach ($result as $r) {
            array_push($id, $r["Valid_Taxon_ID"]);
        }
        return $id;
    }

    /**
     *
     * @param type $db - ADONewConnection
     * @param type $cropID - Crop ID to search from concepts table.
     * @return array  - Array of Taxon objects.
     */
    public function getSysnonyms($taxonID) {
        global $db;
        $query = "SELECT * FROM species WHERE Valid_Taxon_ID = " . $taxonID . " AND Valid_Taxon_ID != Taxon_ID";
        $result = $db->getAll($query);
        $synonyms = array();
        foreach ($result as $r) {
            // Create taxon synonym object.
            $synonym = new Taxon($r["Taxon_ID"]); // Taxon_ID will be the identifier of the suggestion list.
            $synonym->setGenus(trim($r["Genus"]));
            $synonym->setSpecie(trim($r["Species"]));
            $synonym->setSpecieAuthor(trim($r["Species_Author"]));
            $synonym->setSubspecie(trim($r["Subsp"]));
            $synonym->setSubspecieAuthor($r["Subsp_Author"]);
            $synonym->setVariety(trim($r["Var"]));
            $synonym->setVarietyAuthor(trim($r["Var_Author"]));
            $synonym->setForm(trim($r["Form"]));
            $synonym->setFormAuthor(trim($r["Form_Author"]));
            $synonym->setScientificName(trim($r["Scientific_Name"]));
            // insert synonim to the array.
            array_push($synonyms, $synonym);
        }
        return $synonyms;
    }

    public function getTaxon($taxonID) {
        global $db;

        $query = "SELECT * FROM species WHERE Taxon_ID = " . $taxonID;
        $result = $db->getRow($query);
        if (!empty($result)) {
            // Create taxon object.
            $taxon = new Taxon($result["Taxon_ID"]);
            $taxon->setValidTaxonID($result["Valid_Taxon_ID"]);
            $taxon->setFamily($result["Family"]);
            $taxon->setFamilyAuthor($result["FamilyAuthor"]);
            $taxon->setGenus(trim($result["Genus"]));
            $taxon->setSpecie(trim($result["Species"]));
            $taxon->setSpecieAuthor(trim($result["Species_Author"]));
            $taxon->setSubspecie(trim($result["Subsp"]));
            $taxon->setSubspecieAuthor($result["Subsp_Author"]);
            $taxon->setVariety(trim($result["Var"]));
            $taxon->setVarietyAuthor(trim($result["Var_Author"]));
            $taxon->setForm(trim($result["Form"]));
            $taxon->setFormAuthor(trim($result["Form_Author"]));
            $taxon->setMainCrop(trim($result["Main_Crop"]));
            $taxon->setCommonName(trim($result["Common_Name"]));
            
            return $taxon;
        }
        return null;
    }

    public function getTaxonbyName($taxonName) {
        global $db;

        $query = "SELECT * FROM species WHERE Scientific_Name = '" . str_replace("_", " ", $taxonName) . "' AND Main_Crop = 0";
        $result = $db->getRow($query);
        if (!empty($result)) {
            // Create taxon object.
            $taxon = new Taxon($result["Valid_Taxon_ID"]);
            if ($result["Valid_Taxon_ID"] != $result["Taxon_ID"]) { // Get the valid scientific name just in case if is a synonim
                $query = "SELECT * FROM species WHERE Valid_Taxon_ID = " . $result["Valid_Taxon_ID"] . " AND Taxon_ID = Valid_Taxon_ID";
                $result_valid_taxon = $db->getRow($query);

                if (!empty($result_valid_taxon)) {
                    $valid_taxon = new Taxon($result_valid_taxon["Valid_Taxon_ID"]);
                    $valid_taxon->setFamily($result_valid_taxon["Family"]);
                    $valid_taxon->setFamilyAuthor($result_valid_taxon["FamilyAuthor"]);
                    $valid_taxon->setGenus(trim($result_valid_taxon["Genus"]));
                    $valid_taxon->setSpecie(trim($result_valid_taxon["Species"]));
                    $valid_taxon->setSpecieAuthor(trim($result_valid_taxon["Species_Author"]));
                    $valid_taxon->setSubspecie(trim($result_valid_taxon["Subsp"]));
                    $valid_taxon->setSubspecieAuthor($result_valid_taxon["Subsp_Author"]);
                    $valid_taxon->setVariety(trim($result_valid_taxon["Var"]));
                    $valid_taxon->setVarietyAuthor(trim($result_valid_taxon["Var_Author"]));
                    $valid_taxon->setForm(trim($result_valid_taxon["Form"]));
                    $valid_taxon->setFormAuthor(trim($result_valid_taxon["Form_Author"]));
                    $valid_taxon->setMainCrop(trim($result_valid_taxon["Main_Crop"]));
                    
                    // Asignando el nombre valido en base a la informacion del taxon verdadero
                    $taxon->setValidName(trim($valid_taxon->generateScientificName(true,false)));
                }
            }
            $taxon->setFamily($result["Family"]);
            $taxon->setFamilyAuthor($result["FamilyAuthor"]);
            $taxon->setGenus(trim($result["Genus"]));
            $taxon->setSpecie(trim($result["Species"]));
            $taxon->setSpecieAuthor(trim($result["Species_Author"]));
            $taxon->setSubspecie(trim($result["Subsp"]));
            $taxon->setSubspecieAuthor($result["Subsp_Author"]);
            $taxon->setVariety(trim($result["Var"]));
            $taxon->setVarietyAuthor(trim($result["Var_Author"]));
            $taxon->setForm(trim($result["Form"]));
            $taxon->setFormAuthor(trim($result["Form_Author"]));
            $taxon->setMainCrop(trim($result["Main_Crop"]));
            $taxon->setCommonName(trim($result["Common_Name"]));
            
            return $taxon;
        }
        return null;
    }

    /**
     * @global type $db
     * @param type $id
     * @param type $includeCommonName
     * @param type $limit
     * @param type $term
     * @return array
     *
     * @deprecated
     */
    public function _suggestTaxaList($id, $includeCommonName, $limit, $term) {
        global $db;

        // ignore html labels and split by space.
        $searchText = explode(" ", strip_tags($term));

        $taxonomies = array("Genus", "Species", "Subsp", "Var", "Form");

        $query = "SELECT Taxon_ID, ";

        foreach ($taxonomies as $taxonomy) {
            $query .= $taxonomy . ", ";
        }
        $query .= "Common_Name FROM species WHERE (";

        // create where clause
        for ($i = 0; ($i < count($searchText)) && ($i < 2); $i++) {
            $query .= $taxonomies[$i] . " LIKE '" . $searchText[$i];
            if ($i == count($searchText) - 1) {
                $query .= "%'";
            } else {
                $query .= "' AND ";
            }
        }
        $query .= ") AND Main_Crop = " . $id;
        if ($includeCommonName) {
            $query .= " OR Common_Name LIKE '" . strip_tags($term) . "%'";
        }

        $query .= " AND Main_Crop = " . $id . "
                    ORDER BY " . $taxonomies[0] . ", " . $taxonomies[1] . ", " . $taxonomies[2] . " ASC
                        " . (empty($limit) ? "" : "LIMIT " . $limit);

        // create an empty array
        $taxa = array();
        $result = $db->getall($query);
        for ($i = 0; $i < count($result); $i++) {
            // initialize taxon object which will be added to the taxons array.
            $taxon = new Taxon($result[$i]["Taxon_ID"]); // Taxon_ID will be the identifier of the suggestion list.
            $taxon->setCommonName(trim($result[$i]["Common_Name"]));
            $taxon->setGenus(trim($result[$i]["Genus"]));
            $taxon->setSpecie(trim($result[$i]["Species"]));
            $taxon->setSubspecie(trim($result[$i]["Subsp"]));
            $taxon->setVariety(trim($result[$i]["Var"]));
            $taxon->setForm(trim($result[$i]["Form"]));
            array_push($taxa, $taxon);
        }

        return $taxa;
    }

    public function suggestTaxaList($id, $includeCommonName, $limit, $term) {
        global $db;
        /* Nueva consulta sinonimos de Alex funciona para genepool y cwr */
        $query = "SELECT s2.Common_Name, s2.Family, s2.Genus, s2.Species, s2.Subsp, s2.Var, s2.Form, s2.Scientific_Name, s2.Valid_Taxon_ID
                    FROM species s2 INNER JOIN species s ON s2.Valid_Taxon_ID = s.Valid_Taxon_ID
                    WHERE LOWER(s2.Scientific_Name) LIKE '" . strtolower(strip_tags($term)) . "%'";
        $query .= "        AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID";
        if ($includeCommonName) {
            $query .= "     OR s2.Common_Name LIKE '%" . strip_tags($term) . "%' ";
        }
        $query .= "         AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID";
        $query .= "         OR s2.Family LIKE '" . strip_tags($term) . "%' ";
        $query .= "         AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID";
        $query .= "         OR LOWER(s2.Scientific_Name) = '" . strtolower(strip_tags($term)) . "'
                            AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID
                            OR s2.Common_Name = '" . strip_tags($term) . "'
                            AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID
                            OR s2.Family = '" . strip_tags($term) . "'
                            AND s.Main_Crop = " . $id . " AND s.Valid_Taxon_ID = s.Taxon_ID
                            ORDER BY s2.Family, s2.Genus, s2.Species, s2.Subsp, s2.Var ASC
                        " . (empty($limit) ? "" : "LIMIT " . $limit);

        // create an empty array
        $taxa = array();
        $result = $db->getall($query);
        $array = array(); // Almacenar nombres cientificos, no mostrar resultados con mismo nombre cientifico
        for ($i = 0; $i < count($result); $i++) {
            // initialize taxon object which will be added to the taxons array.
            $taxon = new Taxon($result[$i]["Valid_Taxon_ID"]); // Taxon_ID will be the identifier of the suggestion list.
            $taxon->setFamily(trim($results[$i]["Family"]));
            $taxon->setCommonName(trim($result[$i]["Common_Name"]));
            $taxon->setGenus(trim($result[$i]["Genus"]));
            $taxon->setSpecie(trim($result[$i]["Species"]));
            $taxon->setSubspecie(trim($result[$i]["Subsp"]));
            $taxon->setVariety(trim($result[$i]["Var"]));
            $taxon->setForm(trim($result[$i]["Form"]));
            $taxon->setScientificName(trim($result[$i]["Scientific_Name"]));
            if (!in_array($taxon->getScientificName(), $array)) {
                array_push($taxa, $taxon);
            }
            array_push($array, $taxon->getScientificName());
        }

        return $taxa;
    }

    public function updateScientificNames() {
        global $db;

        $query = "SELECT * FROM species WHERE Scientific_Name is NULL ORDER BY Taxon_ID";
        $result = $db->getall($query);
        foreach ($result as $r) {
            // Create taxon synonym object.
            $taxon = new Taxon($r["Taxon_ID"]); // Taxon_ID will be the identifier of the suggestion list.
            $taxon->setGenus(trim($r["Genus"]));
            $taxon->setSpecie(trim($r["Species"]));
            $taxon->setSpecieAuthor(trim($r["Species_Author"]));
            $taxon->setSubspecie(trim($r["Subsp"]));
            $taxon->setSubspecieAuthor($r["Subsp_Author"]);
            $taxon->setVariety(trim($r["Var"]));
            $taxon->setVarietyAuthor(trim($r["Var_Author"]));
            $taxon->setForm(trim($r["Form"]));
            $taxon->setFormAuthor(trim($r["Form_Author"]));
            $scientificName = $taxon->getScientificName(false, false);

            $queryUpdate = "UPDATE species SET Scientific_Name = '" . $scientificName . "' WHERE Taxon_ID = " . $taxon->getId();

            $db->Execute($queryUpdate);

            echo "UPDATED: <b>ID: " . $taxon->getId() . "</b> <i>" . $scientificName . "</i><br>";
        }
    }

    public function getCountTaxaInCountry($term) {
        global $db;
        $query = "SELECT COUNT(distinct d.Taxon_ID) as count
            FROM distribution d
            INNER JOIN species s ON d.Taxon_ID = s.Taxon_ID
            INNER JOIN countries c ON d.Country = c.Code
            WHERE c.Name like '" . addslashes(strip_tags($term)) . "%'
            AND s.Main_Crop = 0";
        $result = $db->getOne($query);
        return $result;
    }

    public function getCountTaxaInRegion($term) {
        global $db;
        $query = "SELECT COUNT(distinct d.Taxon_ID) as count
            FROM distribution d
            INNER JOIN species s ON d.Taxon_ID = s.Taxon_ID
            INNER JOIN countries c ON d.Country = c.Code
            WHERE c.Region like '" . addslashes(strip_tags($term)) . "%'
            AND s.Main_Crop = 0";
        $result = $db->getOne($query);
        return $result;
    }

    public function getTaxaInCountry($term) {
        global $db;
        $query = "SELECT s.*
            FROM distribution d
            INNER JOIN species s ON d.Taxon_ID = s.Taxon_ID
            INNER JOIN countries c ON d.Country = c.Code
            WHERE c.Name like '" . $term . "%'
            AND s.Main_Crop = 0
            GROUP BY s.Taxon_ID
            ORDER BY s.Genus, s.Species, s.Subsp, s.Var ASC";
        $results = $db->getAll($query);
        $taxa = array();
        foreach ($results as $r) {
            $taxon = new Taxon($r["Taxon_ID"]);
            $taxon->setFamily(trim($r["Family"]));
            $taxon->setFamilyAuthor(trim($r["FamilyAuthor"]));
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
            $taxon->setMainCrop(trim($r["Main_Crop"]));
            array_push($taxa, $taxon);
        }
        return $taxa;
    }

    public function getTaxaInRegion($term) {
        global $db;
        $query = "SELECT s.*
            FROM distribution d
            INNER JOIN species s ON d.Taxon_ID = s.Taxon_ID
            INNER JOIN countries c ON d.Country = c.Code
            WHERE c.Region like '" . $term . "%'
            AND s.Main_Crop = 0
            GROUP BY s.Taxon_ID
            ORDER BY s.Genus, s.Species, s.Subsp, s.Var ASC";
        $results = $db->getAll($query);
        $taxa = array();
        foreach ($results as $r) {
            $taxon = new Taxon($r["Taxon_ID"]);
            $taxon->setFamily(trim($r["Family"]));
            $taxon->setFamilyAuthor(trim($r["FamilyAuthor"]));
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
            $taxon->setMainCrop(trim($r["Main_Crop"]));
            $taxon->setScientificName(trim($r["Scientific_Name"]));
            array_push($taxa, $taxon);
        }
        return $taxa;
    }

    public function getTaxaBreedingUse($breedingUse) {
        global $db;
        $query = "SELECT * FROM Breeding_data bd
                INNER JOIN species s ON bd.Taxon_ID = s.Taxon_ID
                WHERE bd.Description LIKE '%" . $breedingUse . "%' GROUP BY s.Taxon_ID ORDER BY Scientific_Name";
        $results = $db->getAll($query);
        $taxa = array();
        foreach ($results as $r) {
            $taxon = new Taxon($r["Taxon_ID"]);
            $taxon->setCommonName(trim($r["Common_Name"]));
            $taxon->setGenus(trim($r["Genus"]));
            $taxon->setSpecie(trim($r["Species"]));
            $taxon->setSubspecie(trim($r["Subsp"]));
            $taxon->setVariety(trim($r["Var"]));
            $taxon->setForm(trim($r["Form"]));
            $taxon->setScientificName(trim($r["Scientific_Name"]));
            array_push($taxa, $taxon);
        }
        return $taxa;
    }

}

?>
