<?php

/**
 * Description of Country
 *
 * @author Héctor F. Tobón R. (htobon)
 */
class Country {

    private $code;
    private $name;
    private $details;
    private $iso2;

    function __construct($code) {
        $this->code = $code;
    }
    
    public function getIso2() {
        return $this->iso2;
    }

    public function setIso2($iso2) {
        $this->iso2 = $iso2;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->name;
    }

    public function getDetails() {
        return $this->details;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDetails($details) {
        $this->details = $details;
    }

}

?>
