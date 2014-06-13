<?php

interface AdvancedSearchDAO {
    public function getResultsAdvancedSearch($term,$search_type,$countries,$regions,$concept_type,$concept_levels,$priority_genera);
}
?>
