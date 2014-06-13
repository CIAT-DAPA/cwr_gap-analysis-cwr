<?php

/*
 * @author: Alex Gabriel Castaneda
 */
interface ConceptDAO{
    public function getAllConceptTypes();
    public function getAllLevelsByType($type);
}

?>
