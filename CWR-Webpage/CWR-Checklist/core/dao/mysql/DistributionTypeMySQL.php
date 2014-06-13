<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/DistributionTypeDAO.php';
require_once WORKSPACE_DIR . 'core/model/DistributionType.php';
require_once WORKSPACE_DIR . 'core/model/Region.php';
require_once WORKSPACE_DIR . 'core/model/Country.php';

class DistributionTypeMySQL implements DistributionTypeDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DistributionTypeMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    /**
     *
     * @param type $db - ADONewConnection
     * @param type $cropID - Crop ID to search from concepts table.
     * @return array - Array of DistributionType objects representing a geographical distribution.
     */
    public function getDistributionTypes($cropID) {
        global $db;
        $query = "SELECT Type FROM distribution WHERE Taxon_ID = " . $cropID . " AND Country IS NOT NULL GROUP BY Type ORDER BY Type ASC";
        $db_regionTypes = $db->getAll($query);
        $distributionTypes = array();
        foreach ($db_regionTypes as $db_regionType) {
            $distributionType = new DistributionType($db_regionType["Type"]);
            // search for Regions, countries and details.
            $query = "SELECT d.ID, c.Code, c.Name, c.Region, c.ISO_Alpha2,
                        GROUP_CONCAT(dd.Detail SEPARATOR ', ') as Details
                        FROM distribution d
                        INNER JOIN countries c ON d.Country = c.Code
                        LEFT JOIN distribution_detail dd ON d.Detail_ID = dd.ID
                        WHERE d.Taxon_ID = " . $cropID . "
                        AND d.Type = '" . $distributionType->getName() . "'
                        group by c.Code
                        ORDER BY c.Region, c.Code, dd.Detail";
            $db_regions = $db->getAll($query);
            $regions = array();
            $currentRegion = "";
            // loop regions
            foreach ($db_regions as $db_region) {
                if ($db_region["Region"] != $currentRegion) {
                    // Add last region. The variable $region must have been created before.
                    $currentRegion != "" ? array_push($regions, $region) : false /* Do Nothing */;
                    $currentRegion = $db_region["Region"];
                    $region = new Region($currentRegion);
                }
                // create a country for the current region.
                $country = new Country($db_region["Code"]);
                $country->setName(trim($db_region["Name"]));
                $country->setIso2(trim($db_region["ISO_Alpha2"]));
                if (!empty($db_region["Details"])) {
                    // details are separated by commas, taked directly from the database.
                    $country->setDetails($db_region["Details"]);
                }
                $countries = $region->getCountries();
                array_push($countries, $country);
                $region->setCountries($countries);
            }
            array_push($regions, $region);
            $distributionType->setRegions($regions);
            // insert region type object to the array.
            array_push($distributionTypes, $distributionType);
        }
        return $distributionTypes;
    }

    

}

?>
