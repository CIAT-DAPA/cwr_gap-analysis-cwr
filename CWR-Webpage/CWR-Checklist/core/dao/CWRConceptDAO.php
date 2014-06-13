<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
interface CWRConceptDAO {
    /**
     * @param String $taxonID - Taxon ID to search from species table
     * @return CWRConcept - CWRConcept with the specified ID and its corresponding taxon.
     */
    public function getCWRConcept($taxonID);
}

?>
