<?php
/**
 *
 * @author Héctor F. Tobón R. (htobon)
 */
interface CountryDAO {
    
    public function suggestCountries($term);
    
    public function getIso2($countryName);
    
    public function getAllCountryNames();
    
    public function getAllRegionNames();
}

?>
