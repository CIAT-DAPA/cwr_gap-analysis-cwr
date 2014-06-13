<?php

/**
 * @author Alex Gabriel Castañeda
 */
class Taxon {

    private $id;
    private $family;
    private $familyAuthor;
    private $genus;
    private $specie;
    private $specieAuthor;
    private $subspecie;
    private $subspecieAuthor;
    private $variety;
    private $varietyAuthor;
    private $form;
    private $formAuthor;
    private $nothosubsp;
    private $nothosubspAuthor;
    private $commonName;
    private $scientificName;
    private $synonyms;
    private $classificationReferences; // array of ClassificationReference objects.
    private $geographicDistributions; // array of DistributionType objects.
    private $geographicDistributionReferences; // array of DistributionReference objects.
    private $mainCrop;
    private $herbaria; // array of Herbarium objects.
    private $utilizations; //array of Utilization Objects.
    private $utilizationReferences; // array of UtilizationReference objects.     

    public function __construct($id) {
        $this->id = $id;
        $this->classificationReferences = array();
        $this->geographicDistributions = array();
        $this->herbaria = array();
        $this->utilizations = array();
        $this->utilizationReferences = array();
        $this->geographicDistributionReferences = array();
    }
    
    public function getScientificName() {
        return $this->scientificName;
    }

    public function setScientificName($scientificName) {
        $this->scientificName = $scientificName;
    }   
    
    public function getGeographicDistributionReferences() {
        return $this->geographicDistributionReferences;
    }

    public function setGeographicDistributionReferences(array $geographicDistributionReferences) {
        $this->geographicDistributionReferences = $geographicDistributionReferences;
    }
        
    /**
     * Get all the countries from its geographic distributions.
     * 
     * return an array of Country objects 
     */
    public function getCountries() {
        $countries = array();
        foreach($this->geographicDistributions as $distributionType){
            foreach($distributionType->getRegions() as $region) {
                foreach($region->getCountries() as $country) {
                    for($i=0;$i < strlen($country->getName());$i++){
                        $str .= ord(substr($country->getName(),$i,1)) . "-"; // Generate the char representation, sep. by - (each letter)
                    }
                    $country->setName($str);
                    array_push($countries, $country);
                    $str = "";
                }
            }
        }     
        
        return $countries;
    }
    
    public function getUtilizations() {
        return $this->utilizations;
    }

    public function setUtilizations(array $utilizations) {
        $this->utilizations = $utilizations;
    }

    public function getUtilizationReferences() {
        return $this->utilizationReferences;
    }

    public function setUtilizationReferences(array $utilizationReferences) {
        $this->utilizationReferences = $utilizationReferences;
    }

        
    public function getHerbaria() {
        return $this->herbaria;
    }

    public function setHerbaria(array $herbaria) {
        $this->herbaria = $herbaria;
    }
        
    public function getMainCrop() {
        return $this->mainCrop;
    }

    public function setMainCrop($mainCrop) {
        $this->mainCrop = $mainCrop;
    }
    
    public function getClassificationReferences() {
        return $this->classificationReferences;
    }

    public function setClassificationReferences(array $classificationReferences) {
        $this->classificationReferences = $classificationReferences;
    }

    public function getGeographicDistributions() {
        return $this->geographicDistributions;
    }

    public function setGeographicDistributions(array $geographicDistributions) {
        $this->geographicDistributions = $geographicDistributions;
    }

    public function getFamily() {
        return $this->family;
    }

    public function getFamilyAuthor() {
        return $this->familyAuthor;
    }

    public function getSubspecieAuthor() {
        return $this->subspecieAuthor;
    }

    public function setSubspecieAuthor($subspecieAuthor) {
        $this->subspecieAuthor = $subspecieAuthor;
    }

    public function getVarietyAuthor() {
        return $this->varietyAuthor;
    }

    public function getFormAuthor() {
        return $this->formAuthor;
    }
    
    public function getNothosubspAuthor() {
        return $this->nothosubspAuthor;
    }

    public function getId() {
        return $this->id;
    }

    public function getForm() {
        return $this->form;
    }

    public function getGenus() {
        return $this->genus;
    }

    public function getSpecie() {
        return $this->specie;
    }

    public function getSubspecie() {
        return $this->subspecie;
    }

    public function getVariety() {
        return $this->variety;
    }
    
    public function getNothosubsp() {
        return $this->nothosubsp;
    }

    public function getCommonName() {
        return $this->commonName;
    }

    public function generateScientificName($showAuthors, $isHTML) {
        $taxa = "";
        // genus
        if (!empty($this->genus)) {
            $taxa .= ($isHTML ? "<i>" : "") . $this->genus . " ";
        }
        // specie
        if (!empty($this->specie)) {
            $taxa .= $this->specie . ($isHTML ? "</i> " : " ");
        }
        // specie author
        if ($showAuthors && !empty($this->specieAuthor)) {
            $taxa .= $this->specieAuthor . " ";
        }
        // subspecie
        if (!empty($this->subspecie)) {
            $taxa .= "subsp." . ($isHTML ? "<i> " : " ") . $this->subspecie . ($isHTML ? "</i> " : " ");
        }
        // subspecie author
        if ($showAuthors && !empty($this->subspecieAuthor)) {
            $taxa .= $this->subspecieAuthor . " ";
        }
        // variety
        if (!empty($this->variety)) {
            $taxa .= "var." . ($isHTML ? "<i> " : " ") . $this->variety . ($isHTML ? "</i> " : " ");
        }
        // variety author
        if ($showAuthors && !empty($this->varietyAuthor)) {
            $taxa .= $this->varietyAuthor . " ";
        }
        // form
        if (!empty($this->form)) {
            $taxa .= "f." . ($isHTML ? "<i> " : " ") . $this->form . ($isHTML ? "</i> " : " ");
        }
        // form author
        if ($showAuthors && !empty($this->formAuthor)) {
            $taxa .= $this->formAuthor . " ";
        }
        // nothosubsp
        if (!empty($this->nothosubsp)) {
            $taxa .= "nothosubsp." . ($isHTML ? "<i> " : " ") . $this->nothosubsp . ($isHTML ? "</i> " : " ");
        }
        // nothosubsp author
        if (!empty($this->nothosubspAuthor)) {
            $taxa .= $this->nothosubspAuthor . " ";
        }
        $taxa = substr($taxa, 0, -1);
        return $taxa;
    }

    public function getSynonyms() {
        return $this->synonyms;
    }

    public function getSpecieAuthor() {
        return $this->specieAuthor;
    }

    public function setSpecieAuthor($specieAuthor) {
        $this->specieAuthor = $specieAuthor;
    }

    public function setGenus($genus) {
        $this->genus = $genus;
    }

    public function setSpecie($specie) {
        $this->specie = $specie;
    }

    public function setSubspecie($subspecie) {
        $this->subspecie = $subspecie;
    }

    public function setVariety($variety) {
        $this->variety = $variety;
    }

    public function setVarietyAuthor($varietyAuthor) {
        $this->varietyAuthor = $varietyAuthor;
    }
    
    public function setCommonName($commonName) {
        $this->commonName = $commonName;
    }

    public function setForm($form) {
        $this->form = $form;
    }

    public function setFormAuthor($formAuthor) {
        $this->formAuthor = $formAuthor;
    }
    
    public function setNothosubsp($nothosubsp) {
        $this->nothosubsp = $nothosubsp;
    }
    
    public function setNothosubspAuthor($nothosubspAuthor) {
        $this->nothosubspAuthor = $nothosubspAuthor;
    }

    public function setSynonyms(array $synonyms) {
        $this->synonyms = $synonyms;
    }

    public function setFamilyAuthor($familyAuthor) {
        $this->familyAuthor = $familyAuthor;
    }

    public function setFamily($family) {
        $this->family = $family;
    }

}

?>
