<?php

/**
 *
 * @author Héctor F. Tobón R. (htobon)
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/GenePoolConceptDAO.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';
require_once WORKSPACE_DIR . 'core/model/Concept.php';
require_once WORKSPACE_DIR . 'core/model/GenePoolConcept.php';

class GenePoolConceptMySQL implements GenePoolConceptDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new GenePoolConceptMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    /**
     *
     * @param type $db - ADONewConnection.
     * @param type $cropID - Crop ID to search from concepts table.
     * @return \GenePoolConcept - GenePoolConcept Object and its Concepts.
     */
    public function getGenePoolConcept($taxonID) {
        global $db;
        
        $query = "SELECT Valid_Taxon_ID FROM species where Taxon_ID = $taxonID LIMIT 1";
        $result = $db->getAll($query);
        
        $validTaxonID = $result[0]["Valid_Taxon_ID"];
        $query = "SELECT DISTINCT c.Crop_ID, c.Concept_ID, c.Concept_Type, c.Concept_Level, s.*
          FROM concepts c
          INNER JOIN species s ON c.Taxon_ID = s.Taxon_ID
          WHERE c.Concept_ID = (
            SELECT c.Concept_ID
            FROM concepts c
            WHERE c.Concept_Level in ('1A','Crop taxa') and c.Taxon_ID = $validTaxonID
          ) 
          ORDER BY c.Concept_Level, s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author, s.Form, s.Form_Author";
        
        $result = $db->getAll($query);
        $genePoolConcept = null;
        $cropID = null;
        // Validate if $result has not data.
        if (!empty($result)) {
            $genePoolConcept = new GenePoolConcept();
            $theConceptID = null;
            $theConceptType = null;
            $array_id = array();
            foreach ($result as $db_concept) {
                $concept = new Concept();
                $concept->setConceptType(trim($db_concept["Concept_Type"]));
                $concept->setConceptLevel(trim($db_concept["Concept_Level"]));
                $concept->setConceptID(trim($db_concept["Concept_ID"]));
                $concept->setCropID(trim($db_concept["Crop_ID"]));
                
                if ($cropID == null) {
                    $cropID = trim($db_concept["Crop_ID"]);
                }
                if ($theConceptID == null) {
                    $theConceptID = $concept->getConceptID();
                }
                if ($theConceptType == null) {
                    $theConceptType = $concept->getConceptType();
                }
                /*                 * ** TAXON - BASIC INFORMATION *** */
                $taxon = new Taxon(trim($db_concept["Valid_Taxon_ID"])); // Taxon_ID will be the identifier of the suggestion list.
                $taxon->setCommonName(trim($db_concept["Common_Name"]));
                $taxon->setFamily(trim($db_concept["Family"]));
                $taxon->setFamilyAuthor(trim($db_concept["Family_Author"]));
                $taxon->setGenus(trim($db_concept["Genus"]));
                $taxon->setSpecie(trim($db_concept["Species"]));
                $taxon->setSpecieAuthor(trim($db_concept["Species_Author"]));
                $taxon->setSubspecie(trim($db_concept["Subsp"]));
                $taxon->setSubspecieAuthor($db_concept["Subsp_Author"]);
                $taxon->setVariety(trim($db_concept["Var"]));
                $taxon->setVarietyAuthor(trim($db_concept["Var_Author"]));
                $taxon->setForm(trim($db_concept["Form"]));
                $taxon->setFormAuthor(trim($db_concept["Form_Author"]));
                $taxon->setMainCrop(trim($db_concept["Main_Crop"]));
                $taxon->setScientificName(trim($db_concept["Scientific_Name"]));

                $concept->setTaxon($taxon);
                
                if ($concept->getConceptLevel() == "Crop taxa") {
                    $genePoolConcept->addCropTaxa($taxon);
                } else {
                    // if concept level = 1A it should be also in the genepool concept list.
                    if ($concept->getConceptLevel() == "1A") {
                        $genePoolConcept->addCropTaxa($taxon);
                    }
                    $genePoolConcept->addConcept($concept);
                }
            }
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $mainTaxon = $taxonDAO->getTaxon($cropID);
            $mainTaxon->setValidName($taxonDAO->getTaxon($taxonID)->generateScientificName(true,false));
            $mainCrop = new Concept($mainTaxon->getId());
            $mainCrop->setConceptID($theConceptID);
            $mainCrop->setConceptType($theConceptType);
            $mainCrop->setCropID($cropID);
            $mainCrop->setTaxon($mainTaxon);
            $genePoolConcept->setMainCrop($mainCrop);
        }
        
        return $genePoolConcept;
    }

}

?>
