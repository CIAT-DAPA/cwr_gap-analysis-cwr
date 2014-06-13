<?php

/*
  Template Name: CWR Species List
 */
/* Initializing WordPress features */
require_once('../../../wp-blog-header.php');
/* Show WordPress Header */
get_header();
/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';
;
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

if (isset($_GET['search-type']) && $_GET['search-type'] != '') {
    $term = $_GET['term'];
    switch ($_GET['search-type']) {
        case "cwr":
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->suggestTaxaList(0, false, '' /* no limit */, $term);
            $long = count($taxa);

            if ($long != 1) {
                $middle = ceil($long / 2);

                foreach ($taxa as $tx) {
                    if ($i < $middle) {
                        $taxa_left[] = $tx;
                    } else {
                        $taxa_right[] = $tx;
                    }
                    $i++;
                }
            } else {
                $taxa_left[] = $taxa[0];
            }

            $smarty->assign("cwrName",$term);
            $smarty->assign("taxa_left", $taxa_left);
            $smarty->assign("taxa_right", $taxa_right);
            $smarty->assign("taxa",$taxa);
            $smarty->assign("searchType", "cwr");
            break;
        case "location":
            // return the suggestions for the typed distribution name
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->getTaxaInCountry($term);
            $long = count($taxa);

            if ($long != 1) {
                $middle = ceil($long / 2);

                foreach ($taxa as $tx) {
                    if ($i < $middle) {
                        $taxa_left[] = $tx;
                    } else {
                        $taxa_right[] = $tx;
                    }
                    $i++;
                }
            } else {
                $taxa_left[] = $taxa[0];
            }

            $smarty->assign("taxa_left", $taxa_left);
            $smarty->assign("taxa_right", $taxa_right);
            $smarty->assign("taxa",$taxa);
            $smarty->assign("searchType", "location");

            $countryDAO = DAOFactory::getDAOFactory()->getCountryDAO();
            $iso2 = $countryDAO->getIso2($term);
            $smarty->assign("countryCode", $iso2);
            $smarty->assign("countryName", $term);

            break;
        case "region":
            // Return a list of taxa for the selected region
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->getTaxaInRegion($term);
            $long = count($taxa);

            if ($long != 1) {
                $middle = ceil($long / 2);

                foreach ($taxa as $tx) {
                    if ($i < $middle) {
                        $taxa_left[] = $tx;
                    } else {
                        $taxa_right[] = $tx;
                    }
                    $i++;
                }
            } else {
                $taxa_left[] = $taxa[0];
            }

            $smarty->assign("taxa_left", $taxa_left);
            $smarty->assign("taxa_right", $taxa_right);
            $smarty->assign("taxa",$taxa);
            $smarty->assign("searchType", "regions");
            $smarty->assign("regionName", $term);
            break;
        case "breeding-use":
            // Return a list of taxa for the selected breeding use string
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->getTaxaBreedingUse($term);
            $long = count($taxa);

            if ($long != 1) {
                $middle = ceil($long / 2);

                foreach ($taxa as $tx) {
                    if ($i < $middle) {
                        $taxa_left[] = $tx;
                    } else {
                        $taxa_right[] = $tx;
                    }
                    $i++;
                }
            } else {
                $taxa_left[] = $taxa[0];
            }

            $smarty->assign("taxa_left", $taxa_left);
            $smarty->assign("taxa_right", $taxa_right);
            $smarty->assign("taxa",$taxa);
            $smarty->assign("searchType", "breedingUse");
            break;
    }
}

$smarty->display("cwr-species-list.tpl");
//get_sidebar();
get_footer();
?>

