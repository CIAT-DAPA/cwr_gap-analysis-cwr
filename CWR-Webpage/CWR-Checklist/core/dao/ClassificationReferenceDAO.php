<?php
/**
 *
 * @author Héctor F. Tobón R. (htobon)
 */
interface ClassificationReferenceDAO {
    /**
     *
     * @param type $db - ADONewConnection
     * @param type $cropID - Crop ID to search from concepts table.
     * @return array - Array of ClassificationReference objects.
     */
    public function getClassificationReferences($cropID);
}

?>
