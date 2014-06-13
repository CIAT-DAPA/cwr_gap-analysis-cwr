<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/CWRConceptDAO.php';
require_once WORKSPACE_DIR . 'core/dao/mysql/TaxonMySQL.php';
require_once WORKSPACE_DIR . 'core/model/CWRConcept.php';

class CWRConceptMySQL implements CWRConceptDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CWRConceptMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getCWRConcept($taxonID) {
        global $db;
        $cwrConcept = new CWRConcept($taxonID);
        $taxon = TaxonMySQL::getInstance()->getTaxon($taxonID);
        if ($taxon != null) {
            $cwrConcept->setTaxon($taxon);
            /* SELECT Concept_Level, Concept_Type FROM concepts WHERE Taxon_ID = (SELECT s.Valid_Taxon_ID FROM species s WHERE Taxon_ID = $taxonID) */
            $query = "SELECT Concept_Level, Concept_Type FROM concepts WHERE Taxon_ID = $taxonID and Concept_Level not in ('Crop taxa')";
            $result = $db->getAll($query);

            if (!empty($result)) {
                foreach ($result as $r) {
                    $cwrConcept->setConceptLevel($r['Concept_Level']);
                    $cwrConcept->setConceptType($r['Concept_Type']);
                }
            } else {
                $query = "SELECT Concept_Level, Concept_Type FROM concepts WHERE Taxon_ID IN (SELECT TaXON_ID from species where Valid_Taxon_ID = $taxonID and Valid_Taxon_ID not in (Taxon_ID)) and Concept_Level not in ('Crop taxa')";

                if (!empty($result)) {
                    foreach ($result as $r) {
                        $cwrConcept->setConceptLevel($r['Concept_Level']);
                        $cwrConcept->setConceptType($r['Concept_Type']);
                    }
                }
            }

            return $cwrConcept;
        } else {
            return null;
        }
    }

    public function getCWRConceptbyTaxonName($taxonName) {
        global $db;
        $cwrConcept = new CWRConcept();
        $taxon = TaxonMySQL::getInstance()->getTaxonbyName($taxonName);
        if ($taxon != null) {
            $cwrConcept->setTaxon($taxon);
            $query = "SELECT Concept_Level, Concept_Type FROM concepts WHERE Taxon_ID = " . $taxon->getId() . " and Concept_Level not in ('Crop taxa')";
            $result = $db->getAll($query);

            if (!empty($result)) {
                foreach ($result as $r) {
                    $cwrConcept->setConceptLevel($r['Concept_Level']);
                    $cwrConcept->setConceptType($r['Concept_Type']);
                }
            } else {
                $query = "SELECT Concept_Level, Concept_Type FROM concepts WHERE Taxon_ID IN (SELECT Taxon_ID from species where Valid_Taxon_ID = " . $taxon->getId() . " and Valid_Taxon_ID not in (Taxon_ID)) and Concept_Level not in ('Crop taxa')";

                if (!empty($result)) {
                    foreach ($result as $r) {
                        $cwrConcept->setConceptLevel($r['Concept_Level']);
                        $cwrConcept->setConceptType($r['Concept_Type']);
                    }
                }
            }

            return $cwrConcept;
        } else {
            return null;
        }
    }

    public function getMainConcept($taxonID) {
         global $db;

        $query = "SELECT c.Concept_Type,c.Concept_Level, s.Valid_Taxon_ID, s.Scientific_Name
                FROM species s
                JOIN concepts c
                WHERE s.Taxon_ID = c.Crop_ID
                AND c.Taxon_ID = $taxonID
                AND c.Concept_Level NOT 
                IN (
                'Crop taxa'
                )
               AND s.Main_Crop = 1 ORDER BY s.Scientific_Name";

        $result = $db->getAll($query);
        $taxa = Array();

        // Entre estas dos condiciones, se debe poner lo de los otros tipos potenciales de uso para el enlace al gene pool
        $hasConInfo = false;
        $hasGraInfo = false;
        $ids = Array();

        if (!empty($result)) { // Tiene informacion de concepto
            $hasConInfo = true;
            foreach ($result as $r) {
                $taxon = new stdClass();
                $taxon->taxon = TaxonMySQL::getInstance()->getTaxon($r["Valid_Taxon_ID"]);
                $taxon->Concept_Type = $r["Concept_Type"];
                $taxon->Concept_Level = $r["Concept_Level"];
                $taxon->Scientific_Name = $r["Scientific_Name"];
                array_push($taxa, $taxon);
                array_push($ids,$r["Valid_Taxon_ID"]); // Ingresando en el arreglo de arrays
            }
        }

        $query = "SELECT b.Pot_Conf, b.Description, b.Crop_ID, s.Scientific_Name FROM Breeding_data b JOIN species s ON s.Taxon_ID = b.Crop_ID WHERE b.Taxon_ID = $taxonID AND b.Description = 'Graftstock' ORDER BY s.Scientific_Name";
        $result = $db->getAll($query);

        if (!empty($result)) { // Tiene informacion de graftstock
            $hasGraInfo = true;
            foreach ($result as $r) { 
                if(!in_array($r["Crop_ID"], $ids, true)) { // Solo ingresar la informacion en caso de que no haya relacion de concept
                    $taxon = new stdClass();
                    $taxon->taxon = TaxonMySQL::getInstance()->getTaxon($r["Crop_ID"]);
                    $taxon->Concept_Level = $r['Pot_Conf'];
                    $taxon->Concept_Type = strtolower($r['Description']);
                    $taxon->Scientific_Name = $r["Scientific_Name"];
                    array_push($taxa, $taxon);
                }
            }
        }

        if (!$hasConInfo && !$hasGraInfo) { // No tiene informacion asociada ni a graftstock ni a concept
            $query = "SELECT b.Pot_Conf, b.Description, b.Crop_ID, s.Scientific_Name FROM Breeding_data b JOIN species s ON s.Taxon_ID = b.Crop_ID WHERE b.Taxon_ID = $taxonID ORDER BY s.Scientific_Name";
            $result = $db->getAll($query);
            $last_id = null;
            
            if (!empty($result)) {
                foreach ($result as $r) {
                    if($r["Crop_ID"] != $last_id){
                        $taxon = new stdClass();
                        $taxon->taxon = TaxonMySQL::getInstance()->getTaxon($r["Crop_ID"]);
                        $taxon->Concept_Level = $r['Pot_Conf'];
                        $taxon->Concept_Type = $r['Description'] . "[PT]"; // Incluir para filtrar por el uso potencial en el enlace de retorno al gene pool
                        $taxon->Scientific_Name = $r["Scientific_Name"];
                        array_push($taxa, $taxon);
                        $last_id = $r["Crop_ID"];  
                    }
                }
            }
        }
        

        return $taxa;
    }
    

    /* Consulta anterior
      public function getMainConcept($taxonID) {
      global $db;

      $query = "SELECT c.Concept_Type,c.Concept_Level, s.Valid_Taxon_ID, s.Scientific_Name
      FROM species s
      JOIN concepts c
      WHERE s.Taxon_ID = c.Crop_ID
      AND c.Taxon_ID = $taxonID
      AND c.Concept_Level NOT
      IN (
      'Crop taxa'
      )
      AND s.Main_Crop = 1 ORDER BY s.Valid_Taxon_ID, c.Concept_Level ASC";
      $result = $db->getAll($query);
      $taxa = Array();

      if (!empty($result)) {
      foreach ($result as $r) {
      $taxon = new stdClass();
      $taxon->taxon = TaxonMySQL::getInstance()->getTaxon($r["Valid_Taxon_ID"]);
      $taxon->Concept_Type = $r["Concept_Type"];
      $taxon->Concept_Level = $r["Concept_Level"];
      array_push($taxa, $taxon);
      }
      } else {
      $query = "SELECT s.Valid_Taxon_ID
      FROM species s
      WHERE
      s.Valid_Taxon_ID IN (
      SELECT b.Crop_ID
      FROM Breeding_data b
      WHERE b.Taxon_ID = $taxonID ) AND s.Main_Crop = 1 ";
      $result = $db->getAll($query);
      if (!empty($result)) {
      foreach ($result as $r) {
      $taxon[] = TaxonMySQL::getInstance()->getTaxon($r["Valid_Taxon_ID"]); // Valid_Taxon_ID
      }
      } else {
      return null;
      }
      }

      return $taxa;
      }
     */

    public function getMainConceptbyTaxonName($taxonName) {
        
    }

}

?>
