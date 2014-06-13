<?php

require_once('../../../wp-blog-header.php');
get_header();

/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

/* Show Wordpress Header */

if (isset($_POST)) {
    $term = $_POST["term"]; // Es el valor del genero, taxon o crop que se esta buscando
    
    if($_POST["term"] == "Enter a genus, taxon or crop name") {
        $term = "";
    }
    
    $concept_type = $_POST["concept-type"]; // Concept type, solo se tendra un valor para esto
    $concept_levels = $_POST["concept-level"]; // Esta es un arreglo, se pueden seleccionar varios
    $countries = $_POST["country"];
    $regions = $_POST["region"];
    $search_type = 0; // Busqueda normal utilizando valores suministrados en termin

    if (isset($_POST["priority-genera-only"])) {
        $priority_genera = $_POST["priority-genera"]; // Array con los generos prioritarios a buscar
        $search_type = 1; // Busqueda por generos prioritarios
    } else if (isset($_POST["priority-croptaxa-only"])) {
        $search_type = 2; // Busqueda realizada por priority taxa
    } else if (empty($countries) && empty($regions)){
        $search_type = 3;
    }

    $advancedSearchDAO = DAOFactory::getDAOFactory()->getAdvancedSearchDAO();
    $taxa = $advancedSearchDAO->getResultsAdvancedSearch($term, $search_type, $countries, $regions, $concept_type, $concept_levels, $priority_genera); // Devuelve un arreglo de objetos taxon
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
    
    //print($query);
   
}

// display TPL
$smarty->assign("taxa_left", $taxa_left);
$smarty->assign("taxa_right", $taxa_right);
$smarty->assign("taxa",$taxa);
$smarty->display("advanced-search-details.tpl");

get_footer();
?>
