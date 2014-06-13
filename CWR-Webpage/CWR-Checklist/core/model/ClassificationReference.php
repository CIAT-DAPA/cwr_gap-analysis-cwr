<?php
/**
 * A representation of the literature reference from which the taxon
 * scientific name and synonyms were taken.
 *
 * @author Alex Gabriel Castañeda
 */
class ClassificationReference {
    private $id;
    private $reference;
    private $website;
    
    function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getReference() {
        return $this->reference;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }
}

?>
