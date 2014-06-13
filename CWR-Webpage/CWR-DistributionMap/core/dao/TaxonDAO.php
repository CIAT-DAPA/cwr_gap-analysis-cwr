<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
interface TaxonDAO {

    /**
     *
     * @param String $taxonID - Taxon ID to search from species table.
     * @return array  - Array of Taxon objects.
     */
    public function getSysnonyms($taxonID);

    /**
     * @param String $taxonID - Taxon ID to search from species table
     * @return Taxon - Taxon object that correspond to the specified ID.
     */
    public function getTaxon($taxonID);
    
    
    /**
     * 
     */
    public function getTaxonbyName($taxonName);
    
    
    /**
     * @param int $mainCrop must be 1 or 0.
     * @param bool $includeCommonName include common name column in the search
     * @param string $term - text that is going to be searched
     * @param limit number of results. NULL value means No Limit.
     * @return an array of Taxon objects.
     */
    public function suggestTaxaList($id, $includeCommonName, $limit, $term);
    
     /**
     * @param type $term - country name or part of it to search.
     * @return number - Number of taxa that are in a country (specified by the term).
     */
    public function getCountTaxaInCountry($term);

    /**
     * @param type $term - country name or part of it to search.
     * @return array - Array of Taxon objects representing those taxa that 
     * are in the specified country name.
     */
    public function getTaxaInCountry($term);
    
    /**
     * @param type $term - region name
     * @return number - Number of taxa that are in a Region
     */
    public function getCountTaxaInRegion($term);
    
    /**
     * @param string $breedingUse - The breeding use to search.
     * @return array - Array of Taxon objects that 
     */
    public function getTaxaBreedingUse($breedingUse);
}


?>
