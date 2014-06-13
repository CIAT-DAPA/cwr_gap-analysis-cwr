<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
require_once WORKSPACE_DIR.'core/dao/mysql/factory/MySQLFactory.php';
abstract class DAOFactory {
    static private $daoFactory; 
    
    final public static function getDAOFactory() {
        global $infoDB;
        if(DAOFactory::$daoFactory == null) {
            switch ($infoDB['driver']) {
            case 'mysql':
                DAOFactory::$daoFactory = new MySQLFactory();
                break;
            default :
                throw new Exception('Database driver not found! - '.$infoDB['driver'].' driver');
                //throw new Exception('Database driver not found! - '.$driver.' driver');
            }
            
        }
        return DAOFactory::$daoFactory;
    
    }
    
    abstract public function getAdvancedSearchDAO();
    
    abstract public function getConceptDAO();
    
    abstract public function getCWRConceptDAO();

    abstract public function getClassificationReferenceDAO();

    abstract public function getConceptReferenceDAO();

    abstract public function getBreedingUseDAO();

    abstract public function getDistributionTypeDAO();
    
    abstract public function getDistributionReferenceDAO();

    abstract public function getGenePoolConceptDAO();

    abstract public function getHerbariumDAO();

    abstract public function getTaxonDAO();
    
    abstract public function getCountryDAO();
    
    abstract public function getUtilizationDAO();
    
    abstract public function getStorageBehaviorDAO();
}

?>
