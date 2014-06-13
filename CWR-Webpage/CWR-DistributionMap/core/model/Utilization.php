<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
class Utilization {
    private $id;
    private $type;
    private $use;
    
    public function __construct($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getUse() {
        return $this->use;
    }   

    public function setType($type) {
        $this->type = $type;
    }

    public function setUse($use) {
        $this->use = $use;
    }   
}

?>
