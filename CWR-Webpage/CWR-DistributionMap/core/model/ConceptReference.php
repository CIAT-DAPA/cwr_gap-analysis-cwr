<?php

/**
 * @author Héctor F. Tobón R. (htobon)
 */
class ConceptReference {

    private $id;
    private $conceptID;
    private $webPage;
    private $reference;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getConceptID() {
        return $this->conceptID;
    }

    public function getWebPage() {
        return $this->webPage;
    }

    public function getReference() {
        return $this->reference;
    }

    public function setConceptID($conceptID) {
        $this->conceptID = $conceptID;
    }

    public function setWebPage($webPage) {
        $this->webPage = $webPage;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

}

?>
