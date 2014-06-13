<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
class Concept {

    private $conceptID;
    private $conceptType;
    private $conceptLevel;
    private $cropID;
    private $taxon; // Taxon object.
    private $general;
    private $conceptReferences; // array of ConceptReference objects.

    public function __construct() {
    }
    
    public function getConceptReferences() {
        return $this->conceptReferences;
    }

    public function setConceptReferences(array $conceptReferences) {
        $this->conceptReferences = $conceptReferences;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getConceptID() {
        return $this->conceptID;
    }

    public function getConceptType() {
        return $this->conceptType;
    }

    public function getConceptLevel() {
        return $this->conceptLevel;
    }

    public function getCropID() {
        return $this->cropID;
    }

    public function getTaxon() {
        return $this->taxon;
    }

    public function getGeneral() {
        return $this->general;
    }

    public function setConceptID($conceptID) {
        $this->conceptID = $conceptID;
    }

    public function setConceptType($conceptType) {
        $this->conceptType = $conceptType;
    }

    public function setConceptLevel($conceptLevel) {
        $this->conceptLevel = $conceptLevel;
    }

    public function setCropID($cropID) {
        $this->cropID = $cropID;
    }

    public function setTaxon(Taxon $taxon) {
        $this->taxon = $taxon;
    }

    public function setGeneral($general) {
        $this->general = $general;
    }

}

?>
