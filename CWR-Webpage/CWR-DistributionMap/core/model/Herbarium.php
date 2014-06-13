<?php

/**
 * @author Alex Gabriel Castañeda
 */
class Herbarium {

    private $id;
    private $countryCode;
    private $institutionCode;
    private $institutionName;
    private $institutionLocation;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getCountryCode() {
        return $this->countryCode;
    }

    public function getInstitutionCode() {
        return $this->institutionCode;
    }

    public function getInstitutionName() {
        return $this->institutionName;
    }

    public function getInstitutionLocation() {
        return $this->institutionLocation;
    }

    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    public function setInstitutionCode($institutionCode) {
        $this->institutionCode = $institutionCode;
    }

    public function setInstitutionName($institutionName) {
        $this->institutionName = $institutionName;
    }

    public function setInstitutionLocation($institutionLocation) {
        $this->institutionLocation = $institutionLocation;
    }

    public function getDetailsInHTML() {
        return "<div>" .
                "<!-- b>Institution Name:</b> " . $this->institutionName . "<br / -->" .
                "<b>Location:</b> " . $this->institutionLocation .
                "</div>";
    }

}

?>
