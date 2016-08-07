<?php

namespace Mst\DoctrineWorkbenchBundle\Models;

/**
 * @author javi
 */
interface ModelTransformer
{   
    /**
     * Parse stdObj fields from UI to an array with fieldMappings
     * 
     * @param array $fields
     * 
     * @return array
     */
    public function generateFieldsMappings(array $fields);

    /**
     * Parse stdObj relations from UI to an array with associationMappings
     * 
     * @param array $entities
     * @param array $relations
     * 
     * @return array
     */
    public function generateAssociationMappings(array $entities, array $relations);
}
