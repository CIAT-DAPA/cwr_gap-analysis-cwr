<?php

/*
 * @Author: Alex Gabriel Castañeda
 * Clase que define lo que contendra un objeto tipo specie, usado para almacenar la taxonomia. Ademas de datos de
 * localizacion por puntos coordenados para el caso de la realizacion del mapa de distribucion por puntos
 */
class Specie {
    private $taxonomy;
    private $cropcode;
    private $distribution_points;
    private $commonName;
    private $taxon_id;
    private $valid_taxon_id;
    private $FPC;
    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Specie();
        }
        return self::$instance;
    }
    
    public function __construct() {
    }
    
    public function setTaxonID($taxon_id) {
        $this->taxon_id = $taxon_id;
    }
    
    public function setValidTaxonID($valid_taxon_id) {
        $this->valid_taxon_id = $valid_taxon_id;
    }
    
    public function setDistributionPoints($distribution_points){
        $this->distribution_points = $distribution_points;
    }
    
    public function setTaxonomy($taxonomy) {
        $this->taxonomy = $taxonomy;
    }
    
    public function setCropCode($cropcode) {
        $this->cropcode = $cropcode;
    }
    
    public function setFPC($FPC) {
        $this->FPC = $FPC;
    }
    
    public function setCommonName($commonName){
        $this->commonName = $commonName;
    }
    
    public function getTaxonomy() {
        return $this->taxonomy;
    }
    
    public function getCropCode() {
        return $this->cropcode;
    }
    
    public function getFPC() {
        return $this->FPC;
    }
    
    public function getDistributionPoints() {
        return $this->distribution_points;
    }
    
    public function getCommonName() {
        return $this->commonName;
    }
    
    public function getTaxonID() {
        return $this->taxon_id;
    }
    
    public function getValidTaxonID() {
        return $this->valid_taxon_id;
    }
    
}
?>
