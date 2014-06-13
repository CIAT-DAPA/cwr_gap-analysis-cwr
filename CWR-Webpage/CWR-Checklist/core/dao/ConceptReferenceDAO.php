<?php
/**
 *
 * @author Alex Gabriel Castañeda
 */
interface ConceptReferenceDAO {
   /**
     *
     * @param type $db - ADONewConnection
     * @param type $conceptID - Concept ID to search from concepts_ref table.
     * @return type array - Array of ConceptReference objects.
     */
    public function getConceptReferences($conceptID);
}

?>
