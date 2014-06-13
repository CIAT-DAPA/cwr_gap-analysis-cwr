<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
interface GenePoolConceptDAO {
    /**
     *
     * @param type $db - ADONewConnection.
     * @param type $cropID - Crop ID to search from concepts table.
     * @return \GenePoolConcept - GenePoolConcept Object and its Concepts.
     */
    public function getGenePoolConcept($cropID);
}

?>
