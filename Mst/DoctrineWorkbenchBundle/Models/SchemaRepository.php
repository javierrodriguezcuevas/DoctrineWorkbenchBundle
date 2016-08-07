<?php

namespace Mst\DoctrineWorkbenchBundle\Models;

use Mst\DoctrineWorkbench\Entity\WorkbenchSchema;

/**
 * @author javi
 */
interface SchemaRepository
{     
    /**
     * Find all WorkbenchSchemas
     * 
     * @return array
     */
    public function findAll();
    
    /**
     * Find one WorkbenchSchema by id
     * 
     * @param integer $id
     * 
     * @return mixed WorkbenchSchema or false on failure.
     */
    public function find($id);
    
    /**
     * Save a WorkbenchSchema
     * 
     * @param WorkbenchSchema $entity
     * 
     * @return bool
     */
    public function save(WorkbenchSchema $entity);
    
    /**
     * Remove a WorkbenchSchema by id
     * 
     * @param integer $id
     * 
     * @return bool
     */
    public function delete($id);
}
