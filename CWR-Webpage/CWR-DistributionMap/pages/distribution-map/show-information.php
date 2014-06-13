<?php


require_once '../../config/db.php';
require_once '../../config/smarty.php';
require_once("../../core/model/Specie.php");
require_once('../../core/dao/mysql/SpecieMySQL.php');

$LOCATION_OCCURRENCES_FILES = "http://gisweb.ciat.cgiar.org/distributionMaps"; //   Ubicacion final de los archivos en servidor GISWEB
$GLOBAL_LOCATION_FILES = "/global-summary/";
$specieDAO = SpecieMySQL::getInstance();

// Este caso unicamente aplica cuando es una busqueda de tipo global donde no se especifica ni una especie o genepool en concreto
if ($_GET['map_type']) {
    switch ($_GET['map_type']) { // Hay dos tipos de mapa para los resultados globales
        case 'global_species_richness' : $ubication = new stdClass();
            $url = $LOCATION_OCCURRENCES_FILES . $GLOBAL_LOCATION_FILES . "global_species_richness"; // Asignando url 
            $ubication->url = $url;
            echo generatePublicKmlURL($ubication);
            break;
        case 'global_gap_richness' : $ubication = new stdClass();
            $url = $LOCATION_OCCURRENCES_FILES . $GLOBAL_LOCATION_FILES . "global_gap_richness"; // Asignando url 
            $ubication->url = $url;
            echo generatePublicKmlURL($ubication);
            break;
    }
}

if ($_GET['specie']) { //Busqueda por especie
    $specie = preg_replace('[\s+]', ' ', $_GET['specie']);
    $specie_object = Specie::getInstance();
    if ($_GET['map_type']) {
        switch ($_GET['map_type']) {
            case 'points' :
                if ($specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)),0)) {
                    $filemanager = @fopen($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)), 0) . "/occurrence_files/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . ".csv", "r");

                    if ($filemanager) { // Solo proceder en caso de que el archivo exista
                        $array_points = array();
                        while (( $data = fgetcsv($filemanager, 1000, ",")) !== FALSE) { // Mientras hay líneas que leer...
                            $i = 0;
                            foreach ($data as $row) {
                                if ($j == 0) { //Ignorar la primera fila, unicamente iniciar datos con taxonomia
                                    $specie_object->setTaxonomy($specie);
                                    break;
                                } else {
                                    if ($i == 0) {
                                        $lat = $row;
                                    }
                                    if ($i == 1) {
                                        $lon = $row;
                                        array_push($array_points, array($lat, $lon));
                                        break;
                                    }
                                }
                                $i++;
                            }
                            $j++;
                        }
                        fclose($filemanager);
                        $specie_object->setDistributionPoints($array_points);
                        echo generateJSONSpecie($specie_object);
                    } else {
                        $data['no_data'] = 1;
                        echo json_encode($data);
                    }
                }else{  // No encuentra el crop code, no hay datos species
                    $data['unknown_specie'] = 1;
                    echo json_encode($data);
                }
                break;
            case 'gap' :   // Devuelve la ubicacion del archivo kml para el mapa Gap
                $filemanager = @fopen($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)), 0) . "/occurrence_files/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . ".csv", "r");
                //echo $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)),0) . "/occurrence_files/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . ".csv";
                if ($filemanager) { // Solo proceder en caso de que el archivo exista
                    $array_points = array();
                    while (( $data = fgetcsv($filemanager, 1000, ",")) !== FALSE) { // Mientras hay líneas que leer...
                        $i = 0;
                        foreach ($data as $row) {
                            if ($j == 0) { //Ignorar la primera fila, unicamente iniciar datos con taxonomia
                                $specie_object->setTaxonomy($specie);
                                break;
                            } else {
                                if ($i == 0) {
                                    $lat = $row;
                                }
                                if ($i == 1) {
                                    $lon = $row;
                                    array_push($array_points, array($lat, $lon));
                                    break;
                                }
                            }
                            $i++;
                        }
                        $j++;
                    }
                    fclose($filemanager);
                    $specie_object->setDistributionPoints($array_points);
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)), 0) . "/models/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))); // Asignando url 
                    //echo $url;
                    if (remoteFileExists($url . "/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . "scaleTestImage.png")) {
                        echo generateJSONSpecieTiles($specie_object, $url);
                    } else { // Existe informacion de narea pero el modelo resultante no fue valido, no se muestran resultados
                        $data['no_data'] = 1;
                        echo json_encode($data);
                    }
                } else { // No es posible que exista un tile si no existe informacion sobre su distribucion en native area
                    $data['no_data'] = 1;
                    echo json_encode($data);
                }
                break;
            case 'gap_spp' : // Devuelve la ubicacion del archivo kml para el mapa gap_spp
                $filemanager = @fopen($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($specie)), 0) . "/occurrence_files/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . ".csv", "r");

                if ($filemanager) { // Solo proceder en caso de que el archivo exista
                    $array_points = array();
                    while (( $data = fgetcsv($filemanager, 1000, ",")) !== FALSE) { // Mientras hay líneas que leer...
                        $i = 0;
                        foreach ($data as $row) {
                            if ($j == 0) { //Ignorar la primera fila, unicamente iniciar datos con taxonomia
                                $specie_object->setTaxonomy($specie);
                                break;
                            } else {
                                if ($i == 0) {
                                    $lat = $row;
                                }
                                if ($i == 1) {
                                    $lon = $row;
                                    array_push($array_points, array($lat, $lon));
                                    break;
                                }
                            }
                            $i++;
                        }
                        $j++;
                    }
                    fclose($filemanager);
                    $specie_object->setDistributionPoints($array_points);
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" .  strtolower($specieDAO->getCropCode(str_replace(" ", "_",$specie), 0)) . "/gap_spp/" . $specieDAO->getFPCByTaxon(str_replace(" ", "_", strtolower($specieDAO->getSpecieName($specie)))) . "/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))); // Asignando url 
                    
                    if (remoteFileExists($url . "/" . str_replace("_subsp.", "", str_replace("_var.", "", str_replace(" ", "_", $specieDAO->getSpecieName($specie)))) . "scaleTestImage.png")) {
                        echo generateJSONSpecieTiles($specie_object, $url);
                    } else { // Existe informacion de narea pero el modelo resultante no fue valido, no se muestran resultados
                        $data['no_data'] = 1;
                        echo json_encode($data);
                    }
                } else {
                    $data['no_data'] = 1;
                    echo json_encode($data);
                }
                break;
        }
    }
}

if ($_GET['genepool']) { // Busqueda por gene pool
    $genepool =  preg_replace('[\s+]', ' ', $_GET['genepool']); // Realiza el cambio de mas de un espacio usando expresiones regulares
    $specieList = Array(); //  Utilizado unicamente cuando se esta realizando busqueda por gene pool, listado con taxons
    if ($_GET['map_type']) {
        switch ($_GET['map_type']) {
            case 'genepool_species_richness' : $ubication = new stdClass();
                $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/species_richness/species-richness/"; // Asignando url 
                $ubication->url = $url;
                if (remoteFileExists($url . "species-richnessscaleTestImage.png")) {
                    echo generatePublicKmlURL($ubication);
                } else {
                    $data['no_data'] = 1;
                    echo json_encode($data);
                }
                break;

            case 'genepool_gap_richness' : $ubication = new stdClass();
                if (remoteFileExists($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/HPS/gap-richness/" . "gap-richnessscaleTestImage.png")) {
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/HPS/gap-richness/"; // Asignando url 
                    $ubication->url = $url;
                    echo generateJSONSpecieTiles($specie_object, $url);
                } else if (remoteFileExists($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/MPS/gap-richness/" . "gap-richnessscaleTestImage.png")) {
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/MPS/gap-richness/"; // Asignando url 
                    $ubication->url = $url;
                    echo generateJSONSpecieTiles($specie_object, $url);
                } else if (remoteFileExists($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/LPS/gap-richness/" . "gap-richnessscaleTestImage.png")) {
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/LPS/gap-richness/"; // Asignando url 
                    $ubication->url = $url;
                    echo generateJSONSpecieTiles($specie_object, $url);
                } else if (remoteFileExists($LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/NFCR/gap-richness/" . "gap-richnessscaleTestImage.png")) {
                    $url = $LOCATION_OCCURRENCES_FILES . "/gap_" . $specieDAO->getCropCode(str_replace(" ", "_", strtolower($genepool)), 1) . "/gap_richness/NFCR/gap-richness/"; // Asignando url 
                    $ubication->url = $url;
                    echo generateJSONSpecieTiles($specie_object, $url);
                } else { // No cumple ninguna de las condiciones, se envia resultado vacio.
                    $data['no_data'] = 1;
                    echo json_encode($data);
                }
                break;
        }
    }
}

/* Funcion para verificar si un archivo existe en un servidor remoto */

function remoteFileExists($url) {
    $curl = curl_init($url);
    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);
    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode == 200) {
            $ret = true;
        }
    }

    curl_close($curl);
    return $ret;
}

/* Devuelve la URL publica con acceso al kml requerido */

function generatePublicKmlURL($arrayUrl) {
    return json_encode($arrayUrl);
}

/* Genera la respuesta para puntos simples de una especie */

function generateJSONSpecie($specie) {
    $pointList = array();
    foreach ($specie->getDistributionPoints() as $data) {
        $point = new stdClass();
        $point->latitude = $data[1]; // latitude
        $point->longitude = $data[0]; // longitude
        array_push($pointList, $point);
    }
    return json_encode($pointList);
}

/* Genera la specie para los tiles incluyendo con informacion de puntos para redireccionamiento */

function generateJSONSpecieTiles($specie, $tileURL) {
    $pointList = array();
    $tileObject = new stdClass();

    if ($tileURL != null) { // Si la url no es nula
        $tileObject->url = $tileURL;
    }

    if ($specie != null) {
        foreach ($specie->getDistributionPoints() as $data) {
            $point = new stdClass();
            $point->latitude = $data[1]; // latitude
            $point->longitude = $data[0]; // longitude
            array_push($pointList, $point);
        }

        // Una vez finalizado el ciclo que recorre los puntos, se debe asignar los puntos extraidos en el objeto tile
        $tileObject->pointList = $pointList;
    }

    return json_encode($tileObject);
}

/* Genera todos los puntos para la respuesta de los puntos de distribucion de un genero */

function generateJSONSpecieList($specieList) {
    $pointList = array();
    foreach ($specieList as $specie) {
        foreach ($specie->getDistributionPoints() as $data) {
            $point = new stdClass();
            $point->latitude = $data[1]; // latitude
            $point->longitude = $data[0]; // longitude
            array_push($pointList, $point);
        }
    }
    return json_encode($pointList);
}
?>

