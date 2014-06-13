<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
class DistributionReference {
    private $id;
    private $reference;
    private $webpage;
    
    public function __construct($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getReference() {
        return $this->reference;
    }

    public function getWebpage() {
        return $this->webpage;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

    public function setWebpage($webpage) {
        $this->webpage = $webpage;
    }
}

?>
