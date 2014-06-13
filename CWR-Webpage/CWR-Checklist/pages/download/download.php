<? ob_start(); ?>
<?php

require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';
require_once WORKSPACE_DIR . 'core/model/Concept.php';

/* Recibe los id's de los taxon, con base a esta informacion se obtiene la demas informacion
 * del taxon. Ademas de su relacion de genepool / cwr segun sea el caso.
 */
if ($_POST["term"]) {
    $ids = $_POST['term'];

    if (!is_array($ids)) {
        $filename = $term . "-" . date("d-M-Y H-i-s") . ".csv";
    } else {
        $filename = "Results-" . date("d-M-Y H-i-s") . ".csv";
    }
    $file = fopen($filename, "w");
    $sep = ",";
    $header = "Id" . $sep . "Family" . $sep . "FamilyAuthor" . $sep . "Genus" . $sep . "Specie" . $sep . "SpecieAuthor" . $sep . "Subspecie" .
            $sep . "SubspecieAuthor" . $sep . "Variety" . $sep . "VarietyAuthor" . $sep . "commonName" . $sep . "ScientificName" . $sep .
            "ConceptType" . $sep . "ConceptLevel" . $sep . "Relative of" . $sep . "\n";

    // deben imprimir en archivo
    $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
    $genePoolConceptDAO = DAOFactory::getDAOFactory()->getGenePoolConceptDAO();
    $conceptCWRDAO = DAOFactory::getDAOFactory()->getCWRConceptDAO();
    $data = $header;
    foreach ($ids as $id) {
        $taxon = $taxonDAO->getTaxon($id);
        if ($taxon->getMainCrop() == 0) {
            $concept = $conceptCWRDAO->getCWRConcept($taxon->getId());
            $mainCrop = $conceptCWRDAO->getMainConcept($taxon->getId());
        } else {
            $concepts = $genePoolConceptDAO->getGenePoolConcept($taxon->getId());
            foreach ($concepts as $c) {
                $concept = $c;
                break;
            }
        }

        $data .= $taxon->getId() . $sep . str_replace(",", " ", $taxon->getFamily()) . $sep . str_replace(",", " ", $taxon->getFamilyAuthor()) . $sep . str_replace(",", " ", $taxon->getGenus()) . $sep . str_replace(",", " ", $taxon->getSpecie()) .
                $sep . str_replace(",", " ", $taxon->getSpecieAuthor()) . $sep . str_replace(",", " ", $taxon->getSubspecie()) . $sep . str_replace(",", " ", $taxon->getSubspecieAuthor()) . $sep . $taxon->getVariety() . $sep . str_replace(",", " ", $taxon->getVarietyAuthor()) .
                $sep . str_replace(",", " ", $taxon->getCommonName()) . $sep . str_replace(",", " ", $taxon->generateScientificName(false, false));
        
       if($mainCrop){
            $data .= $sep;
            foreach($mainCrop as $concept_types){
                $data .= str_replace(",", " ", $concept_types->Concept_Type) . " / ";
            }
            $data .= $sep;
            $data = str_replace("/ ,", ",", $data);
            foreach($mainCrop as $concept_levels){
               $data .= str_replace(",", " ", $concept_levels->Concept_Level) . " / ";  
            }
            $data .= $sep;
            $data = str_replace("/ ,", ",", $data);
            foreach($mainCrop as $names) {
                $data.= $names->Scientific_Name . " / ";
            }
            $data .= $sep;
            $data = str_replace("/ ,", "", $data);
        }
                
        $data .= "\n";
    }

    fwrite($file, $data);
    fclose($file);

    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=" . $filename . " ");
    header("Concet-Transfer-Encoding: utf-8");
    header("Content-length: " . filesize($filename));
    readfile($filename);
    unlink($filename);
}


/* Ideado principalmente para la descarga desde el boton de gene pool */
if ($_GET["term"]) {
    $ids = $_GET['term'];

    if (!is_array($ids)) {
        $filename = $term . "-" . date("d-M-Y H-i-s") . ".csv";
    } else {
        $filename = "Results-" . date("d-M-Y H-i-s") . ".csv";
    }
    $file = fopen($filename, "w");
    $sep = ",";
    $header = "Id" . $sep . "Family" . $sep . "FamilyAuthor" . $sep . "Genus" . $sep . "Specie" . $sep . "SpecieAuthor" . $sep . "Subspecie" .
            $sep . "SubspecieAuthor" . $sep . "Variety" . $sep . "VarietyAuthor" . $sep . "commonName" . $sep . "ScientificName" . $sep .
            "ConceptType" . $sep . "ConceptLevel" . $sep . "Relative of" . $sep . "\n";

    $genePoolConceptDAO = DAOFactory::getDAOFactory()->getGenePoolConceptDAO();
    $data = $header;
    foreach($ids as $id) {
        $concepts = $genePoolConceptDAO->getGenePoolConcept($id);
        $arrayConcepts = $concepts->getConcepts();
        $mainTaxon = $concepts->getMainCrop();
        foreach ($arrayConcepts as $concept) {
            $taxon = $concept->getTaxon();
            $data .= $taxon->getId() . $sep . str_replace(",", " ", $taxon->getFamily()) . $sep . str_replace(",", " ", $taxon->getFamilyAuthor()) . $sep . str_replace(",", " ", $taxon->getGenus()) . $sep . str_replace(",", " ", $taxon->getSpecie()) .
                    $sep . str_replace(",", " ", $taxon->getSpecieAuthor()) . $sep . str_replace(",", " ", $taxon->getSubspecie()) . $sep . str_replace(",", " ", $taxon->getSubspecieAuthor()) . $sep . $taxon->getVariety() . $sep . str_replace(",", " ", $taxon->getVarietyAuthor()) .
                    $sep . str_replace(",", " ", $taxon->getCommonName()) . $sep . str_replace(",", " ", $taxon->generateScientificName(false, false)) . $sep . str_replace(",", " ", $concept->getConceptType()) . $sep . str_replace(",", " ", $concept->getConceptLevel()) .
                    $sep . $mainTaxon->getTaxon()->generateScientificName(false,false);
            $data .= "\n";
        }
    }

    fwrite($file, $data);
    fclose($file);

    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=" . $filename . " ");
    header("Concet-Transfer-Encoding: utf-8");
    header("Content-length: " . filesize($filename));
    readfile($filename);
    unlink($filename);
}
?>
<? ob_flush(); ?>
