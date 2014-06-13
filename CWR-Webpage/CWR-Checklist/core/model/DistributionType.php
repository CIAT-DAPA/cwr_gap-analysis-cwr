<?php

/**
 * Description of RegionType
 *
 * @author Alex Gabriel Castañeda
 */
class DistributionType {

    private $name;
    private $regions; // Array of Region objects

    function __construct($name) {
        $this->name = $name;
        $this->regions = array();
    }

    public function getName() {
        return $this->name;
    }

    public function getRegions() {
        return $this->regions;
    }

    public function setRegions(array $regions) {
        $this->regions = $regions;
    }

}

?>
