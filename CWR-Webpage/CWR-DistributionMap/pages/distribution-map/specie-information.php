<?php

/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';
require_once('../../core/dao/mysql/SpecieMySQL.php');
require_once('../../core/dao/mysql/TaxonMySQL.php');
require_once WORKSPACE_DIR . 'core/model/Concept.php';
require_once WORKSPACE_DIR . 'core/model/GenePoolConcept.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

/* Show Wordpress Header */
if (isset($_GET['specie']) && $_GET['specie'] != '' && !isset($_GET['searchType'])) {
    $taxonName = preg_replace('[\s+]', ' ', $_GET['specie']);
    echo generateJSONConceptInformation($taxonName);
}

if (isset($_GET['searchType']) && $_GET['searchType'] == 'getFPC') {
    $taxonName = preg_replace('[\s+]', ' ', $_GET['specie']);
    echo generateJSONTaxonInformation($taxonName);
}

if (isset($_GET['searchType']) && $_GET['searchType'] == 'genepool') {
    $genepool = preg_replace('[\s+]', ' ', $_GET['genepool']);
    echo generateJSONGenepoolInformation($genepool);
}

if (isset($_GET['searchType']) && $_GET['searchType'] == 'specieList') {
    $taxon = preg_replace('[\s+]', ' ', $_GET['taxon']);

    echo generateJSONSpecieList($taxon);
}

function generateJSONGenepoolInformation($genepool) {
    $specieDAO = SpecieMySQL::getInstance();
    $genepoolID = $specieDAO->getGenepoolID($genepool); // Contiene el id del genepool
    $genepoolConceptDAO = DAOFactory::getDAOFactory()->getGenePoolConceptDAO();
    $genepoolConcept = $genepoolConceptDAO->getGenePoolConcept($genepoolID);

    if ($genepoolConcept != null) {
        if ($genepoolConcept != null) {
            $taxonGroupConcept = $genepoolConcept->getTaxaByConceptLevels();
            $mainCrop = $genepoolConcept->getMainCrop();
        }

        if ($genepoolConceptDAO != null) {
            $r = new stdClass();
            if ($genepoolConcept != null) {
                $r->genepoolName = utf8_encode($mainCrop->getTaxon()->generateScientificName(true, true));
                $r->rawScientificName = utf8_encode($mainCrop->getTaxon()->getScientificName());
                $r->id = $mainCrop->getTaxon()->getId();
                $r->commonName = $mainCrop->getTaxon()->getCommonName();

                if ($mainCrop->getTaxon()->generateScientificName(true, false) != $mainCrop->getTaxon()->getValidName()) {
                    $r->synonimName = utf8_encode($mainCrop->getTaxon()->getValidName());
                }

                $genepoolConcepts = array();
                foreach ($taxonGroupConcept as $conceptLevel => $concepts) {
                    $taxaInformation = array();
                    foreach ($concepts as $concept) {
                        $taxon = new stdClass();
                        $taxon->scientificName = utf8_encode($concept->generateScientificName(false, true));
                        $taxon->ID = $concept->getId();
                        array_push($taxaInformation, $taxon);
                    }
                    array_push($genepoolConcepts, array($conceptLevel, $taxaInformation));
                    unset($conceptNames);
                }
                $r->genepoolConcepts = $genepoolConcepts;
            } else {
                $r->genepoolName = null;
                $r->id = null;
            }
        }
    }else{
        $taxonDAO = TaxonMySQL::getInstance();
        $taxon = $taxonDAO->getTaxonbyName($genepool);
        $r = new stdClass();
        $r->id = $taxon->getId();
        $r->genepoolName = utf8_encode($taxon->generateScientificName(true, false));
    }
    
    //print_r($r);

    return json_encode($r);
}

function generateJSONTaxonInformation($taxonName) {
    $specieDAO = SpecieMySQL::getInstance();
    $taxon = $specieDAO->getCWRSpeciesTaxon($taxonName);

    if ($taxon != null) {
        $r = new stdClass();
        $r->FPCAT = $taxon->getFPC();
    }

    return json_encode($r);
}

function generateJSONConceptInformation($taxonName) {
    $cwrConceptDAO = DAOFactory::getDAOFactory()->getCWRConceptDAO();
    $cwrConcept = $cwrConceptDAO->getCWRConceptbyTaxonName($taxonName);
    $mainTaxon = $cwrConceptDAO->getMainConcept($cwrConcept->getTaxon()->getId());

    if ($cwrConcept != null && $mainTaxon != null) {
        $r = new stdClass();
        $r->specieName = utf8_encode($cwrConcept->getTaxon()->generateScientificName(true, true)); 

        $validName = $cwrConcept->getTaxon()->getValidName();
        if (!empty($validName)) {
            $r->validName = utf8_encode($validName);
        }

        $commonName = $cwrConcept->getTaxon()->getCommonName();
        if (!empty($commonName)) {
            $r->commonName = utf8_encode($commonName);
        }

        $taxonID = $cwrConcept->getTaxon()->getId();
        if (!empty($taxonID)) {
            $r->taxonID = $taxonID;
        }

        $mainCropList = array();
        if ($mainTaxon != null) {
            foreach ($mainTaxon as $crop) {
                $mainCrop = new stdClass();
                $mainCrop->mainCropID = $crop->taxon->getId();
                $mainCrop->type = $crop->Concept_Type;
                $mainCrop->level = $crop->Concept_Level;
                $mainCrop->mainCropName = utf8_encode($crop->taxon->generateScientificName(true, false));
                array_push($mainCropList, $mainCrop);
            }
        }
        $r->mainCropList = $mainCropList;
    } else { // No tiene un concept asociado en la base de datos
        $taxonDAO = TaxonMySQL::getInstance();
        $taxon = $taxonDAO->getTaxonbyName($taxonName);
        $r = new stdClass();
        $r->taxonID = $taxon->getId();
        $r->specieName = utf8_encode($taxon->generateScientificName(true, false));
    }

    return json_encode($r);
}

function isValidSpecie($taxon) {
    $specieDAO = SpecieMySQL::getInstance();
    $result = $specieDAO->isValidSpecie($taxon);
    if ($result == 1) { // 
        return true;
    } else {
        return false;
    }
}

function generateJSONSpecieList() {
    $specieDAO = SpecieMySQL::getInstance();
    $specieList = $specieDAO->getSpeciesByCropCode();
    $result = array();

    if ($specieList != null) {
        foreach ($specieList as $specie) {
            $r = new stdClass();
            $r->Scientific_name = $specie->getTaxonomy();
            $r->Taxon_ID = $specie->getTaxonID();
            $r->Valid_Taxon_ID = $specie->getValidTaxonID();
            $r->Common_Name = $specie->getCommonName();
            $r->Crop_code = ucfirst($specie->getCropCode());
            array_push($result, $r);
        }
    }

    return json_encode($result);
}

?>