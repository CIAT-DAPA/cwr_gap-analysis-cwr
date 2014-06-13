<?php
/**
 * @author Alex Gabriel Castañeda
 */

/* Initializing WordPress features */
require_once('../../../wp-blog-header.php');
get_header();

/* Initializing Smarty Object*/
require_once('../../config/smarty.php');
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

// Get all the breeding uses.
$breedingDAO = DAOFactory::getDAOFactory()->getBreedingUseDAO();
$breedingUses = $breedingDAO->getAllBreedingUses();

// Get all countries.
$countryDAO = DAOFactory::getDAOFactory()->getCountryDAO();
$countries = $countryDAO->getAllCountryNames();
$regions = $countryDAO->getAllRegionNames();

$conceptDAO = DAOFactory::getDAOFactory()->getConceptDAO();
$concept_types = $conceptDAO->getAllConceptTypes();

/* Esta informacion no esta disponible en nuestra base de datos se debe entonces
   Generar un listado manual con los generos que son considerados prioritarios*/
$priority_genera = array();
array_push($priority_genera,"Avena");
array_push($priority_genera,"Aegilops");
array_push($priority_genera,'Amblyopyrum');
array_push($priority_genera,'Cajanus');
array_push($priority_genera,'Cicer');
array_push($priority_genera,'Daucus');
array_push($priority_genera,'Eleusine');
array_push($priority_genera,'Ensete');
array_push($priority_genera,'Helianthus');
array_push($priority_genera,'Hordeum');
array_push($priority_genera,'Ipomoea');
array_push($priority_genera,'Lathyrus');
array_push($priority_genera,'Lens');
array_push($priority_genera,'Malus');
array_push($priority_genera,'Medicago');
array_push($priority_genera,'Musa');
array_push($priority_genera,'Ochthochloa');
array_push($priority_genera,'Oryza');
array_push($priority_genera,'Pennisetum');
array_push($priority_genera,'Phaseolus');
array_push($priority_genera,'Pisum');
array_push($priority_genera,'Secale');
array_push($priority_genera,'Solanum');
array_push($priority_genera,'Sorghum');
array_push($priority_genera,'Tornabenea');
array_push($priority_genera,'Triticum');
array_push($priority_genera,'Vavilovia');
array_push($priority_genera,'Vicia');
array_push($priority_genera,'Vigna');

$smarty->assign("priority_genera",$priority_genera);
$smarty->assign("countries", $countries);
$smarty->assign("regions",$regions);
$smarty->assign("breedingUses", $breedingUses);
$smarty->assign("concept_types", $concept_types);

 $firephp = new FirePHP(true); //TODO - Remove this variable when the development of this page finalize.
 $firephp->log($breedingUses, 'USES:'); // Only for test
//$query = "SELECT DISTINCT Util_Type from Utilisation ORDER BY Util_Type ASC;";
//$uses = $db->getall($query);

//$smarty->assign("uses", $uses);

// display TPL
$smarty->display("search.tpl");

//get_sidebar();
get_footer();

?>