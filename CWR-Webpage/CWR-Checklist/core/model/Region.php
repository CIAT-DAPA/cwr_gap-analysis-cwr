<?php

/**
 * @author Héctor F. Tobón R. (htobon)
 */
class Region {

    private $name;
    private $countries;

    function __construct($name) {
        $this->name = $name;
        $this->countries = array();
    }

    public function getName() {
        return $this->name;
    }

    public function getCountries() {
        return $this->countries;
    }

    public function setCountries(array $countries) {
        $this->countries = $countries;
    }

}

?>
