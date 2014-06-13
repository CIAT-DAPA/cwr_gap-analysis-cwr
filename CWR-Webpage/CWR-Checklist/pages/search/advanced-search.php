<?php
require_once '../../config/config.php';
require_once('../../config/smarty.php');
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

if (isset($_GET['search-type']) && $_GET['search-type'] != '') {
    $term = $_GET['term'];
    switch ($_GET['search-type']) {
        case "concept-level":
            $conceptDAO = DAOFactory::getDAOFactory()->getConceptDAO();
            $concept_level = $conceptDAO->getAllLevelsByType($term);            
            echo generateJSONConceptLevel($concept_level);
            break;
    }
}

function generateJSONConceptLevel($concept_level) {
    $result = array();
    foreach($concept_level as $level) {
        array_push($result, $level);
    }
    return json_encode($result);
}

?>
