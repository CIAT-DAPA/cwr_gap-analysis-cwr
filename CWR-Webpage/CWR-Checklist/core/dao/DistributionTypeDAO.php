<?php

/**
 *
 * @author Héctor F. Tobón R. (htobon)
 */
interface DistributionTypeDAO {

    /**
     *     
     * @param type $cropID - Crop ID to search from concepts table.
     * @return array - Array of DistributionType objects representing a geographical distribution.
     */
    public function getDistributionTypes($cropID);
}

?>
