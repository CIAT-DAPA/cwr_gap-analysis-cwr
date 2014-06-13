<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
class StorageBehavior {
    private $id;
    private $orthodox;
    private $intermeduate;
    private $recalcitrant;
    private $unknown;
    private $reference;
    
    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getOrthodox() {
        return $this->orthodox;
    }

    public function getIntermeduate() {
        return $this->intermeduate;
    }

    public function getRecalcitrant() {
        return $this->recalcitrant;
    }

    public function getUnknown() {
        return $this->unknown;
    }

    public function getReference() {
        return $this->reference;
    }

    public function setOrthodox($orthodox) {
        $this->orthodox = $orthodox;
    }

    public function setIntermeduate($intermeduate) {
        $this->intermeduate = $intermeduate;
    }

    public function setRecalcitrant($recalcitrant) {
        $this->recalcitrant = $recalcitrant;
    }

    public function setUnknown($unknown) {
        $this->unknown = $unknown;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }
    
}

?>
