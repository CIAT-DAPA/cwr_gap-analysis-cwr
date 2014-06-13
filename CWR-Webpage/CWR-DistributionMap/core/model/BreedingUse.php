<?php
/**
 * @author Héctor F. Tobón R. (htobon)
 */
class BreedingUse {
    private $id;
    private $useType;
    private $description;
    private $reference;
    private $taxon;

    public function __construct($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function getUseType() {
        return $this->useType;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getReference() {
        return $this->reference;
    }

    public function getTaxon() {
        return $this->taxon;
    }

    public function setUseType($useType) {
        $this->useType = $useType;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

    public function setTaxon(Taxon $taxon) {
        $this->taxon = $taxon;
    }
}

?>
