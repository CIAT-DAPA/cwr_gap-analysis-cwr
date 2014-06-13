<?php
require_once '../../config/config.php';
require('../../config/smarty.php');
require_once WORKSPACE_DIR . 'core/model/Country.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

if (isset($_GET['specie_id']) && $_GET['specie_id'] != '') {
    $taxonID = $_GET['specie_id'];
    
    $countryDAO = DAOFactory::getDAOFactory()->getCountryDAO();
    //TODO
}

?>
