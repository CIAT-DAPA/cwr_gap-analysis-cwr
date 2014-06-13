<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'core/dao/mysql/AdvancedSearchMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/CWRConceptMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/ClassificationReferenceMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/ConceptReferenceMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/BreedingUseMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/DistributionTypeMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/DistributionReferenceMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/GenePoolConceptMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/HerbariumMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/TaxonMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/CountryMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/UtilizationMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/UtilizationReferenceMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/StorageBehaviorMySQL.php';
require_once WORKSPACE_DIR.'core/dao/mysql/ConceptMySQL.php';
class MySQLFactory extends DAOFactory {
    public function getAdvancedSearchDAO() {
    return AdvancedSearchMySQL::getInstance();
    }
    public function getConceptDAO(){
        return ConceptMySQL::getInstance();
    }
    public function getCWRConceptDAO() {
        return CWRConceptMySQL::getInstance();
    }
    public function getClassificationReferenceDAO() {
        return ClassificationReferenceMySQL::getInstance();
    }
    public function getConceptReferenceDAO() {
        return ConceptReferenceMySQL::getInstance();
    }
    public function getBreedingUseDAO() {
        return BreedingUseMySQL::getInstance();
    }
    public function getDistributionTypeDAO() {
        return DistributionTypeMySQL::getInstance();
    }
    public function getDistributionReferenceDAO() {
        return DistributionReferenceMySQL::getInstance();
    }
    public function getGenePoolConceptDAO() {
        return GenePoolConceptMySQL::getInstance();        
    }
    public function getHerbariumDAO() {
        return HerbariumMySQL::getInstance();
    }
    public function getTaxonDAO() {
        return TaxonMySQL::getInstance();
    }
    public function getCountryDAO() {
        return CountryMySQL::getInstance();
    }
    public function getUtilizationDAO() {
        return UtilizationMySQL::getInstance();
    }    
    public function getUtilizationReferenceDAO() {
        return UtilizationReferenceMySQL::getInstance();
    }
    public function getStorageBehaviorDAO() {
        return StorageBehaviorMySQL::getInstance();
    }
}

?>
