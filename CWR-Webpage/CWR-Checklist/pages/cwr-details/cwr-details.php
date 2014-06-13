<?php

/* Initializing WordPress features */
require_once('../../../wp-blog-header.php');
get_header();

/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

/* Show Wordpress Header */


if (isset($_GET['specie_id']) && $_GET['specie_id'] != '') {
    $taxonID = $_GET['specie_id'];
    // create CWR Concept object.
    $cwrConceptDAO = DAOFactory::getDAOFactory()->getCWRConceptDAO();
    $cwrConcept = $cwrConceptDAO->getCWRConcept($taxonID);
    $mainTaxon = $cwrConceptDAO->getMainConcept($taxonID);

    if ($cwrConcept != null) {
        /*         * ** MAIN SYNONYMS *** */
        $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
        $id = $taxonDAO->isSynonym($taxonID);
        
        if(count($id) > 0) { // Es sinonimo de algun crop, obtener el id y vincularlo
            header("Location: /checklist/cwr-details.php?specie_id=".$id[0]) ;
        }
        
        $synonyms = $taxonDAO->getSysnonyms($taxonID);
        if (count($synonyms) > 0) {
            $cwrConcept->getTaxon()->setSynonyms($synonyms);
        }
        /*         * ** CLASSIFICATION REFERENCES *** */
        $classificationReferenceDAO = DAOFactory::getDAOFactory()->getClassificationReferenceDAO();
        $classificationReferences = $classificationReferenceDAO->getClassificationReferences($taxonID);
        if (count($classificationReferences) > 0) {
            $cwrConcept->getTaxon()->setClassificationReferences($classificationReferences);
        }

        /*         * ** GEOGRAPHIC DISTRIBUTION *** */
        $distributionTypeDAO = DAOFactory::getDAOFactory()->getDistributionTypeDAO();
        $distributionTypes = $distributionTypeDAO->getDistributionTypes($taxonID);
        // if this taxon has geographical distribution information.
        if (count($distributionTypes) > 0) {
            $cwrConcept->getTaxon()->setGeographicDistributions($distributionTypes);
        }

        /*         * ** GEOGRAPHIC DISTRIBUTION REFERENCES *** */
        $distributionReferenceDAO = DAOFactory::getDAOFactory()->getDistributionReferenceDAO();
        $distributionReferences = $distributionReferenceDAO->getDistributionReferences($taxonID);
        if (count($distributionReferences) > 0) {
            $cwrConcept->getTaxon()->setGeographicDistributionReferences($distributionReferences);
        }

        /*         * ** TAXON USAGE *** */
        $utilizationDAO = DAOFactory::getDAOFactory()->getUtilizationDAO();
        $utilizations = $utilizationDAO->getUtilizations($taxonID);
        if (count($utilizations) > 0) {
            $cwrConcept->getTaxon()->setUtilizations($utilizations);
        }

        /*         * ** TAXON USAGE REFERENCES *** */
        $utilizationReferenceDAO = DAOFactory::getDAOFactory()->getUtilizationReferenceDAO();
        $utilizationReferences = $utilizationReferenceDAO->getUtilizationReferences($taxonID);
        if (count($utilizationReferences) > 0) {
            $cwrConcept->getTaxon()->setUtilizationReferences($utilizationReferences);
        }

        /*         * ** CROP BREEDING USES *** */
        $taxonBreedingDAO = DAOFactory::getDAOFactory()->getBreedingUseDAO();
        $taxonBreedingUses = $taxonBreedingDAO->getTaxonBreedingUses($taxonID);
        if (count($taxonBreedingUses) > 0) {
            $cwrConcept->setTaxonBreedingUses($taxonBreedingUses);
        }

        /*         * ** STORAGE BEHAVIOR *** */
        $storageBehaviorDAO = DAOFactory::getDAOFactory()->getStorageBehaviorDAO();
        $storageBehavior = $storageBehaviorDAO->getStorageBehavior($cwrConcept->getTaxon()->getGenus());
        if ($storageBehavior != null) {
            $cwrConcept->setStorageBehavior($storageBehavior);
        }

        /*         * ** HERBARIA *** */
        $herbariumDAO = DAOFactory::getDAOFactory()->getHerbariumDAO();
        $herbaria = $herbariumDAO->getHerbariaData($cwrConcept->getTaxon()->getID());
        if (count($herbaria) > 0) {
            $cwrConcept->getTaxon()->setHerbaria($herbaria);
        }
        
    }
    $firephp = new FirePHP(true); //TODO - Remove this variable when the development of this page finalize.
    $firephp->log($cwrConcept, 'CWR:'); // Only for test
    $smarty->assign("taxon", $cwrConcept->getTaxon());
    $smarty->assign("mainTaxon", $mainTaxon);
    $smarty->assign("cwr", $cwrConcept);
}



// display TPL
$smarty->display("cwr-details.tpl");

// display sidebar
//get_sidebar();
//
// display footer
get_footer();
?>