<?php

require_once '../../config/config.php';
require_once('../../config/smarty.php');
require_once WORKSPACE_DIR . 'core/model/Taxon.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

if (isset($_GET['search-type']) && $_GET['search-type'] != '') {
    $term = $_GET['term'];
    $limit = $_GET['limit'];
    switch ($_GET['search-type']) {
        case "advanced":
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->suggestTaxaList(1, true, $limit, $term);
            $taxa = array_merge($taxa, $taxonDAO->suggestTaxaList(0, true, $limit, $term));
            echo generateJSONTaxa($taxa, true, $term);
            break;
        case "genepool":
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->suggestTaxaList(1, true, $limit, $term);
            echo generateJSONTaxa($taxa, true, $term);
            break;

        case "cwr":
            // Return the suggestions for the typed taxon name
            $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
            $taxa = $taxonDAO->suggestTaxaList(0, false, $limit, $term);
            echo generateJSONTaxa($taxa, false, $term);
            break;

        case "location":
            // Return a country suggestion list for the typed distribution name
            $countryDAO = DAOFactory::getDAOFactory()->getCountryDAO();
            $countries = $countryDAO->suggestCountries($term);
            echo generateJSONCountries($countries);
            break;
        case "taxa-location":
            // Get a country suggestion list for the typed distribution name
            $countryDAO = DAOFactory::getDAOFactory()->getCountryDAO();
            $countries = $countryDAO->suggestCountries($term);
            echo generateJSONCountriesTaxaQuantity($countries);
            break;
        case "taxa-regions":
            // Generate taxa quantity for the region
            echo generateJSONRegionsTaxaQuantity($term);
            break;
    }
}

function generateJSONCountriesTaxaQuantity($countries) {
    $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
    $result = array();
    foreach ($countries as $country) {
        $r = new stdClass();
        $r->countryName = $country->getName();
        $r->countryCode = $country->getCode();
        $r->taxaCount = $taxonDAO->getCountTaxaInCountry($r->countryName);
        array_push($result, $r);
    }
    return json_encode($result);
}

function generateJSONRegionsTaxaQuantity($region) {
    $taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();
    $result = array();

    $r = new stdClass();
    $r->regionName = utf8_encode($region);
    $r->taxaCount = $taxonDAO->getCountTaxaInRegion($r->regionName);
    array_push($result, $r);

    return json_encode($result);
}

function generateJSONCountries($countries) {
    $suggestionList = array();
    foreach ($countries as $country) {
        $suggestion = new stdClass();
        $suggestion->id = $country->getCode();
        $suggestion->value = utf8_encode($country->getName());
        array_push($suggestionList, $suggestion);
    }
    return json_encode($suggestionList);
}

function generateJSONTaxa($taxa, $includeCommonName, $term) {
    $suggestionList = array();
    $array = array();
    $commonNameArray = array();
    foreach ($taxa as $taxon) {
        if(!in_array(utf8_encode($taxon->getScientificName()),$array)) { // El nombre cientifico ya esta en el array?
            $has_same_cn = false;
            $suggestion = new stdClass();
            $suggestion->id = $taxon->getId();
            $suggestion->value = utf8_encode($taxon->getFamily());
            $suggestion->commonName = utf8_encode($taxon->getCommonName());
            $suggestion->scientificName = utf8_encode($taxon->getScientificName());
            if ($includeCommonName && stripos($taxon->getCommonName(), strip_tags($term)) !== false && $taxon->getGenus() != $taxon->getCommonName()) {
                // show common name instead of scientific name.
                if(!in_array($suggestion->commonName,$commonNameArray)){
                    $suggestion->value = utf8_encode($taxon->getCommonName());
                    array_push($commonNameArray,utf8_encode($taxon->getCommonName()));
                }else { // En caso de estar en el arreglo no mostrar informacion sobre su common name en la busqueda   
                    $has_same_cn =  true; // Tiene el mismo common name que una especie que ya se ha consultado, eliminar esta de la busqueda?
                }        
            } else {
                // show scientific name instead of common name.
                // insert only the Genus Name as value in the suggestion list.
                if ($taxon->getGenus() != $genus) {
                    $genusTaxon = new stdClass();
                    $genusTaxon->id = "-9999";
                    $genus = $taxon->getGenus();
                    $genusTaxon->value = utf8_encode($genus);
                    array_push($suggestionList, $genusTaxon);
                }
                // create value string (this value is going to be showed in the interface).
                $suggestion->value = utf8_encode($taxon->getScientificName(false, false));
            }
            array_push($array,utf8_encode($taxon->getScientificName()));  // Anadiendo el nombre cientifico para no agregar resutlados con mismo S.N
            //if(!$has_same_cn){
            array_push($suggestionList, $suggestion);
            //}else{ // Solo incluir el id para mostrar los resultados en la pagina
                /*
                $suggestion_only_id = new stdClass();
                $suggestion_only_id->id = $taxon->getId();
                array_push($suggestionList,$suggestion_only_id);*/
            //}
        }
    }
    return json_encode($suggestionList);
}

?>
