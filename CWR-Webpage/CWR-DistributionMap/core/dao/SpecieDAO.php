<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
interface SpecieDAO {
    
    public function getSpecieTaxaList();
    
    public function getCropCodeList();
    
    public function getCropCode($taxon,$searchType);
    
    public function getSpecieName($taxon);
    
    public function getTaxaListByGenus ($genus);
    
    public function getCWRSpeciesTaxon ($taxon);
    
    public function getCommonNames();
    
}

?>
