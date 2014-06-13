<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
interface BreedingUseDAO {
    public function getCropBreedingUses($cropID);
    
    public function getTaxonBreedingUses($taxonID);
    
    public function getAllBreedingUses();
}

?>
