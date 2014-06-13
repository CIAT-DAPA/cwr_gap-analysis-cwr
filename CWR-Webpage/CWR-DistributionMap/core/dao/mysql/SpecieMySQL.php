<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/SpecieDAO.php';
require_once WORKSPACE_DIR . 'core/model/Specie.php';

class SpecieMySQL implements SpecieDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SpecieMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    /*
     * Metodo para devolver el valor de verdad 
     */

    public function isValidSpecie($taxon) {
        global $db;
        $query = "SELECT c.Valid_Taxon_ID FROM cwr_occurrences_species c JOIN species s ON s.Valid_Taxon_ID = c.Valid_Taxon_ID 
                JOIN species s2 ON s2.Valid_Taxon_ID = s.Valid_Taxon_ID 
                WHERE s2.Taxon_ID = s.Valid_Taxon_ID AND s.Scientific_name LIKE '$taxon%'";

        $result = $db->getAll($query);

        if (empty($result)) { // Si el resultado es vacio debe devolver 0 indicando que no hay datos encontrados para ese taxon en la base de datos
            return 0;
        } else { // La specie suministrada contiende datos validos y hay informacion en la base de datos
            return 1;
        }
    }

    /**
     * El proposito es devolver el id necesario para obtener los resultados posteriores
     */
    public function getGenepoolID($taxon) {
        global $db;
        $query = "SELECT Taxon_ID FROM species s 
                  WHERE Scientific_Name = '$taxon'
                  AND Main_Crop = 1
                  AND Valid_Taxon_ID = Taxon_ID
                 OR Common_Name = '$taxon'
                 AND Main_Crop = 1
                 AND Valid_Taxon_ID = Taxon_ID
                 GROUP BY Taxon_ID";

        $result = $db->getAll($query);

        if (empty($result)) {
            $query = "SELECT s2.Taxon_ID FROM species s JOIN species s2 ON s.Valid_Taxon_ID = s2.Valid_Taxon_ID
               WHERE s.Scientific_Name = '$taxon' AND s.Valid_Taxon_ID = s2.Taxon_ID AND s2.Main_Crop = 1
               OR s.Common_Name = '$taxon' AND s.Valid_Taxon_ID = s2.Taxon_ID AND s2.Main_Crop = 1
               GROUP BY s2.Taxon_ID";
            $result = $db->getAll($query);
        }

        return $result[0]["Taxon_ID"];
    }

    public function getCWRSpeciesTaxon($taxon) {
        global $db;
        $query = "SELECT c.CWR_Scientific_Name, c.FPCAT, c.Crop_Code FROM cwr_occurrences_species c 
        JOIN species s ON s.Valid_Taxon_ID = c.Valid_Taxon_ID
        where  s.Scientific_Name = '" . str_replace("_", " ", $taxon) . "' AND c.FPCAT IS NOT NULL AND s.Main_Crop = 0 LIMIT 1";
        $result = $db->getAll($query);

        $specie = new Specie();
        $specie->setTaxonomy(str_replace("_", " ", $result[0]["CWR_Scientific_Name"]));
        $specie->setFPC($result[0]["FPCAT"]);
        $specie->setCropCode($result[0]["Crop_Code"]);

        return $specie;
    }

    /**
     * Retorna el listado de taxonomias existentes en la tabla
     */
    public function getSpecieTaxaList() {
        global $db;

        $query = "SELECT s2.Scientific_Name
                    FROM species s2 INNER JOIN species s ON s2.Valid_Taxon_ID = s.Valid_Taxon_ID
                    JOIN cwr_occurrences_species c ON s2.Valid_Taxon_ID = c.Valid_Taxon_ID
                    WHERE s2.Main_Crop = 0
                  GROUP BY s2.Scientific_Name
                  ORDER BY s2.Scientific_Name ASC";

        // Se realiza el cambio de s.Main_Crop = 0 a s2.Main_Crop = 0

        $result = $db->getAll($query);
        $species = array();
        foreach ($result as $r) {
            $specie = new Specie();
            $specie->setTaxonomy(str_replace("_", " ", $r["Scientific_Name"]));
            // insert specie to the array.
            array_push($species, $specie);
        }

        return $species;
    }

    /* Revisar esta no esta funcionando */

    public function getCropCodeList() {
        global $db;

        $query = "SELECT s2.Scientific_Name 
                  FROM species s2 
                  INNER JOIN species s ON s2.Valid_Taxon_ID = s.Valid_Taxon_ID 
                  JOIN cwr_occurrences_species c ON s2.Valid_Taxon_ID = c.Valid_Taxon_ID
                  WHERE s.Main_Crop = 1 AND c.Main_Crop_ID = s.Valid_Taxon_ID
                  GROUP BY s2.Scientific_Name
                  ORDER BY s2.Scientific_Name ASC";
        $result = $db->getAll($query);
        $species = array();
        foreach ($result as $r) {
            $specie = new Specie();
            $specie->setCropCode($r["Scientific_Name"]);
            // insert specie to the array.
            array_push($species, $specie);
        }
        return $species;
    }

    public function getCommonNames() {
        global $db;
        $query = "SELECT s2.Common_Name FROM species s join species s2 where s2.Valid_Taxon_ID = s.Valid_Taxon_ID and s.Valid_Taxon_ID in (SELECT Valid_Taxon_ID FROM cwr_occurrences_species GROUP BY Valid_Taxon_ID) AND s2.Common_Name is not null AND s2.Main_Crop = 1 group by Common_Name";
        $result = $db->getAll($query);
        $species = array();
        foreach ($result as $r) {
            $specie = new Specie();
            $specie->setCropCode($r["Common_Name"]);
            // insert specie to the array.
            array_push($species, $specie);
        }
        return $species;
    }

    /**
     * Devuelve el crop code asignado segun la taxonomia ingresada por el usuario
     * @param type $taxon 
     */

    public function getCropCode($taxon, $searchType) {
        global $db;

        if (!is_numeric($taxon)) { // Comprueba que la entrada sea un numero, esto ocurre solo para los no prioritarios
            $query = "SELECT c.Crop_Code FROM cwr_occurrences_species c
                    JOIN species s
                    JOIN species s2
                    WHERE c.Valid_Taxon_ID = s2.Valid_Taxon_ID
                    AND c.Crop_Code IS NOT NULL
                    AND s.Valid_Taxon_ID = s2.Valid_Taxon_ID
                    AND s2.Taxon_ID = s2.Valid_Taxon_ID
                    AND s.Scientific_name = '" . str_replace("_", " ", $taxon) . "'
                    AND s2.Main_Crop = $searchType
                    OR c.Valid_Taxon_ID = s2.Valid_Taxon_ID
                    AND c.Crop_Code IS NOT NULL
                    AND s.Valid_Taxon_ID = s2.Valid_Taxon_ID
                    AND s2.Taxon_ID = s2.Valid_Taxon_ID
                    AND s.Common_Name = '" . str_replace("_", " ", $taxon) . "'
                    AND s2.Main_Crop = $searchType
                    GROUP BY s2.Scientific_name
                    LIMIT 1";
        } else { // Hacer la consulta directa en la tabla cwr_occurrences_species
            $query = "SELECT c.Crop_Code FROM cwr_occurrences_species c WHERE c.Valid_Taxon_ID = $taxon";
        }

        $result = $db->getAll($query);

        if ($result) {
            foreach ($result as $r) {
                $crop_code = $r["Crop_Code"];
            }
        }

        return $crop_code;
    }

    public function getSpecieName($taxon) {
        global $db;

        $query = "SELECT c.CWR_Scientific_Name FROM cwr_occurrences_species c
        JOIN species s ON c.Valid_Taxon_ID = s.Valid_Taxon_ID
        WHERE s.Scientific_Name = '$taxon' AND c.FPCAT IS NOT NULL
        AND s.Main_Crop = 0
        LIMIT 1";

        $result = $db->getAll($query);

        foreach ($result as $r) {
            $taxon = $r["CWR_Scientific_Name"];
        }

        return $taxon;
    }

    public function getFPCByTaxon($taxon) {
        global $db;

        if (!is_numeric($taxon)) {
            $query = "SELECT DISTINCT c.FPCAT FROM cwr_occurrences_species c JOIN species s ON s.Valid_Taxon_ID = c.Valid_Taxon_ID
            where  c.CWR_Scientific_Name = '" . str_replace(" ", "_", $taxon) . "' AND c.FPCAT IS NOT NULL AND s.Main_Crop = 0 LIMIT 1";
        } else {
            $query = "SELECT DISTINCT c.FPCAT FROM cwr_occurrences_species c WHERE c.FPCAT IS NOT NULL AND c.Valid_Taxon_ID = $taxon";
        }

        $result = $db->getAll($query);

        foreach ($result as $r) {
            $fpcat = $r["FPCAT"];
        }

        return $fpcat;
    }

    /*
     * Devuelve un listado de taxas por el genero ingresado
     * @param string $genus
     * @return devuelve un array de objetos especies con la taxonomia obtenida para el genus ingresado
     */

    public function getTaxaListByGenus($genus) {
        global $db;
        $query = "SELECT CWR_Scientific_Name FROM cwr_occurrences_species where taxon like '$genus%'";
        $result = $db->getAll($query);
        $species = array();
        foreach ($result as $r) {
            $specie = new Specie();
            $specie->setTaxonomy(str_replace("_", " ", $r["taxon"]));
            // insert specie to the array.
            array_push($species, $specie);
        }
        return $species;
    }

    /* Devuelve la lista de species que forman parte de un cropcode */

    public function getSpeciesByCropCode() {
        global $db;
        $query = "select s.*, c.Crop_code from cwr_occurrences_species c join species s on s.Valid_Taxon_ID = c.Valid_Taxon_ID 
        WHERE c.Crop_code in ('avena','bambara','bean','cajanus','cicer','cowpea','daucus','eggplant','eleusine',
        'faba_bean','helianthus','hordeum','ipomoea','lathyrus','lens','lima_bean','malus','medicago','musa',
        'pennisetum','pisum','potato','rice','secale','sorghum','triticum','vetch') 
        group by s.Taxon_ID order by c.Crop_code";
        $result = $db->getAll($query);
        $species = array();
        foreach ($result as $r) { // Se obtiene la informacion de la specie de acuerdo al crop code ingresado
            $specie = new Specie();
            $specie->setTaxonomy($r["Scientific_Name"]);
            $specie->setTaxonID($r["Taxon_ID"]);
            $specie->setValidTaxonID($r["Valid_Taxon_ID"]);
            $specie->setCommonName($r["Common_Name"]);
            $specie->setCropCode($r["Crop_code"]);
            array_push($species, $specie);
        }
        return $species;
    }

}

?>
