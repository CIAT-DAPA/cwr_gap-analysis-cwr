<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/CountryDAO.php';
require_once WORKSPACE_DIR . 'core/model/Country.php';

class CountryMySQL implements CountryDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CountryMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    /**
     * Return an associated array of countries to be used for the suggestion tool.
     * @global type $db
     * @param type $term
     * @return array - associated array;
     */
    public function suggestCountries($term) {
        global $db;
        $query = "SELECT code, name, ISO_Alpha2 FROM countries WHERE name LIKE '" . strip_tags($term) . "%' ORDER BY name LIMIT 10";
        //echo($query);
        // make query
        $result = $db->getall($query);

        // initialize arrays.
        $countries = array();

        foreach ($result as $r) {
            $country = new Country($r["code"]);
            $country->setName(trim($r["name"]));
            $country->setIso2(trim($r["ISO_Alpha2"]));

            array_push($countries, $country);
        }
        return $countries;
    }
    
    public function getIso2($countryName) {
        global $db;
        $query = "SELECT ISO_Alpha2 FROM countries where Name LIKE '".$countryName."%'";
        $result = $db->getall($query);
        if(count($result) == 1) {
            return strtolower($result[0]['ISO_Alpha2']);
        }
        return null;
    }
    
    public function getAllCountryNames(){
        global $db;
        $query = "SELECT Name FROM countries ORDER BY Name";
        $result = $db->getall($query);
        
        foreach($result as $rs){
            $countriesnames[] = $rs[0];
        }
        
        return $countriesnames;
    }
    
    public function getAllRegionNames(){
        global $db;
        $query = "SELECT DISTINCT(Region) as Region FROM countries ORDER BY Region";
        $result = $db->getall($query);
        
        foreach($result as $rs){
            $regions[] = $rs[0];
        }
        
        return $regions;
    }

}

?>
