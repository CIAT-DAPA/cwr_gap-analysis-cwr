<?php

/*
 * @Author: Alex Gabriel Castañeda
 * Aqui deberian inicializarse las variables para el autocompletar, queda para discusion
 * si se debe realizar por medio de la base de datos o no
 */
require_once('../../config/smarty.php');
require_once('../../core/dao/mysql/SpecieMySQL.php');

// Obteniendo la taxonomia directamente desde la bd
$specieDAO = SpecieMySQL::getInstance(); // Objeto de clase specie mysql
$taxonomyList = $specieDAO->getSpecieTaxaList(); // Generando la lista de taxonomias
$cropcodeList =  $specieDAO->getCropCodeList();
$cropcodeListFinal = array_merge($cropcodeList,$specieDAO->getCommonNames());

$smarty->assign("taxonomyList", $taxonomyList);
$smarty->assign("cropcodeList", $cropcodeListFinal);
$smarty->display("distribution-map.tpl");

?>
