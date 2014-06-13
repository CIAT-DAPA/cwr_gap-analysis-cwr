<?php

/**
 * Description of GenePoolConcept
 *
 * @author hftobon
 */
class GenePoolConcept {

    private $mainCrop; // Concept object.
    private $cropTaxa; // Array of Taxon objects.
    private $concepts; // Array of Concept objects. (Section: Taxon Group Concepts)
    private $cropBreedingUses; // Array of BreedingUse objects.

    public function __construct() {
        $this->concepts = array();
        $this->cropTaxa = array();
        $this->cropBreedingUses = array();
    }

    public function getCropBreedingUses() {
        return $this->cropBreedingUse;
    }

    public function setCropBreedingUses(array $cropBreedingUses) {
        $this->cropBreedingUses = $cropBreedingUses;
    }

    /**
     * The concept id is the same for all concepts in this Gene Pool Concept.
     * That's why we use the concept that owns to the mainCrop object.
     */
    public function getConceptID() {
        return $this->mainCrop->getConceptID();
    }

    public function getConceptLevels() {
        $conceptLevels = array();
        $level = "";
        foreach ($this->concepts as $concept) {
            if ($concept->getConceptLevel() != $level) {
                array_push($conceptLevels, $concept->getConceptLevel());
                $level = $concept->getConceptLevel();
            }
        }
        return $conceptLevels;
    }

    /**
     * This function returns a bidimensional array corresponding to:
     * Column 1: Concept Level (Primary, Secondary, etc..)
     * Column 2: Array of Taxon objects.
     * Example: $taxonConceptLevels = array('Primary' => array(t1, t2, t3, t4), 'Secondary' => array(t1, t2))
     * @return array
     */
    public function getTaxaByConceptLevels() {
        $taxonConceptLevels = array();
        foreach ($this->concepts as $concept) {
            if ($taxonConceptLevels[$concept->getConceptLevel()] == null) {
                $taxonConceptLevels[$concept->getConceptLevel()] = array();
            }
            array_push($taxonConceptLevels[$concept->getConceptLevel()], $concept->getTaxon());
        }
        return $taxonConceptLevels;
    }

    public function getCropBreedingByUseType() {
        $cropBreedingUseTypes = array();
        foreach ($this->cropBreedingUses as $cropBreeding) {
            if ($cropBreedingUseTypes[$cropBreeding->getUseType()] == null) {
                $cropBreedingUseTypes[$cropBreeding->getUseType()] = array();
            }
            array_push($cropBreedingUseTypes[$cropBreeding->getUseType()], $cropBreeding);
        }
        return $cropBreedingUseTypes;
    }

    public function getMainCrop() {
        return $this->mainCrop;
    }

    public function setMainCrop(Concept $cropTaxon) {
        $this->mainCrop = $cropTaxon;
    }

    public function getCropTaxa() {
        return $this->cropTaxa;
    }

    public function addConcept(Concept $concept) {
        array_push($this->concepts, $concept);
    }

    public function addCropTaxa(Taxon $cropTaxa) {
        array_push($this->cropTaxa, $cropTaxa);
    }

    public function setCropTaxa(array $cropTaxa) {
        $this->cropTaxa = $cropTaxa;
    }

    public function getConcepts() {
        return $this->concepts;
    }

    public function setConcepts($concepts) {
        $this->concepts = $concepts;
    }

}

?>
