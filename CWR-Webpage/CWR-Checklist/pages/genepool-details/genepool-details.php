<?php

/* Initializing WordPress features */
require_once('../../../wp-blog-header.php');
/* Show WordPress Header */
get_header();
/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';



$firephp = new FirePHP(true); //TODO - Remove this variable when the development of this page finalize.

if (isset($_GET['id']) && $_GET['id'] != '') {

    $cropID = $_GET['id'];
    // Search for this gene pool. Unique ids only
    $cropID = array_unique($cropID);
    $cropID = array_values($cropID);
    
    $genePoolConceptDAO = DAOFactory::getDAOFactory()->getGenePoolConceptDAO();
    $genePools = array();
    $taxa = array();
    $crop_taxa =  array();
    $taxons = array();
    foreach($cropID as $cropid) {
        $genePoolConcept = $genePoolConceptDAO->getGenePoolConcept($cropid);
        $firephp->log($genePoolConcept);
        if ($genePoolConcept != null) {
            /*             * ** MAIN SYNONYMS *** */
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $synonyms = $taxonDAO->getSysnonyms($cropid);
            if (count($synonyms) > 0) {
                $genePoolConcept->getMainCrop()->getTaxon()->setSynonyms($synonyms);
            }
            /*             * ** CLASSIFICATION REFERENCES *** */
            $classificationReferenceDAO = DAOFactory::getDAOFactory()->getClassificationReferenceDAO();
            $classificationReferences = $classificationReferenceDAO->getClassificationReferences($cropid);
            if (count($classificationReferences) > 0) {
                $genePoolConcept->getMainCrop()->getTaxon()->setClassificationReferences($classificationReferences);
            }

            /*             * ** TAXON GROUP CONCEPT *** */
            /* The taxon group concept can be extracted using the method getTaxonOrdereByConceptLevels()
             * of GenePoolConcept class.  */

            /*             * ** CONCEPT REFERENCES *** */
            $conceptReferenceDAO = DAOFactory::getDAOFactory()->getConceptReferenceDAO();
            $conceptReferences = $conceptReferenceDAO->getConceptReferences($genePoolConcept->getConceptID());
            if (count($conceptReferences) > 0) {
                $genePoolConcept->getMainCrop()->setConceptReferences($conceptReferences);
            }

            /*             * ** CROP BREEDING USES *** */
            $breedingDAO = DAOFactory::getDAOFactory()->getBreedingUseDAO();
            $cropBreedingUses = $breedingDAO->getCropBreedingUses($cropid);
            if (count($cropBreedingUses) > 0) {
                $genePoolConcept->setCropBreedingUses($cropBreedingUses);
            }

            /*             * ** HERBARIA *** */
            $herbariumDAO = DAOFactory::getDAOFactory()->getHerbariumDAO();
            $herbaria = $herbariumDAO->getHerbariaData($genePoolConcept->getMainCrop()->getTaxon()->getID());
            if (count($herbaria) > 0) {
                $genePoolConcept->getMainCrop()->getTaxon()->setHerbaria($herbaria);
            }  
            
            array_push($taxons,$taxonDAO->getTaxon($cropid));
            array_push($genePools,$genePoolConcept);
            array_push($taxa,$genePoolConcept->getMainCrop()->getTaxon());
            array_push($crop_taxa,$genePoolConcept->getCropTaxa());
        }
    }

    $smarty->assign("genePoolsIDs", $cropID);
    $smarty->assign("taxa",$taxa);
    $smarty->assign("taxons",$taxons);
    $smarty->assign("cropTaxa",$crop_taxa);
    $smarty->assign("genePools", $genePools);
}



// display TPL
$smarty->display("genepool-details.tpl");

// display sidebar
//get_sidebar();
// display footer
get_footer();
?>