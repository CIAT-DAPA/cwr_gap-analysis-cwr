<?php

/**
 *
 * @author Héctor F. Tobón R. (htobon)
 */
class CWRConcept {

    private $id;
    private $taxon;
    private $mainTaxon;
    private $conceptLevel;
    private $conceptType;
    private $taxonBreedingUses; // Array of CropBreedingUse objects.
    private $storageBehavior; // reference to a StorageBehavior object.

    function __construct($id) {
        $this->id = $id;
        $this->taxonBreedingUses = array();
    }

    public function getStorageBehavior() {
        return $this->storageBehavior;
    }

    public function setStorageBehavior(StorageBehavior $storageBehavior) {
        $this->storageBehavior = $storageBehavior;
    }

    public function getId() {
        return $this->id;
    }

    public function getTaxon() {
        return $this->taxon;
    }

    public function setTaxon(Taxon $taxon) {
        $this->taxon = $taxon;
    }
    
    public function getMainTaxon() { 
        return $this->mainTaxon;
    }
    
    public function setConceptLevel($level){
        $this->conceptLevel = $level;
    }
    
    public function getConceptLevel() {
        return $this->conceptLevel;
    }
    
    public function setConceptType($type) {
        $this->conceptType = $type;
    }
    
    public function getConceptType() {
        return $this->conceptType;
    }
    
    public function setMainTaxon(Taxon $taxon) {
        $this->mainTaxon = $taxon;
    }

    public function getCropBreedingUses() {
        return $this->taxonBreedingUses;
    }

    public function setTaxonBreedingUses(array $taxonBreedingUses) {
        $this->taxonBreedingUses = $taxonBreedingUses;
    }

    public function getTaxonBreedingByUseType() {
        $taxonBreedingUseTypes = array();

        foreach ($this->taxonBreedingUses as $taxonBreeding) {
            if ($taxonBreedingUseTypes[$taxonBreeding->getUseType()] == null) {
                $taxonBreedingUseTypes[$taxonBreeding->getUseType()] = array();
            }
            array_push($taxonBreedingUseTypes[$taxonBreeding->getUseType()], $taxonBreeding);
        }
        return $taxonBreedingUseTypes;
    }
}

?>
