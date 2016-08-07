<?php

namespace Mst\DoctrineWorkbenchBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Mst\DoctrineWorkbenchBundle\Models\SchemaRepository;
use Mst\DoctrineWorkbench\Entity\WorkbenchSchema;

/**
 * @author javi
 */
class DoctrineSchemaRepository implements SchemaRepository
{ 
    /** @var EntityManager */
    private $em;
    
    /**
     * @param EntityManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * Find all WorkbenchSchemas
     * 
     * @return array
     */
    public function findAll() 
    {
        return $this->em->getRepository('MstDoctrineWorkbenchBundle:WorkbenchSchema')->findAll();
    }
    
    /**
     * Find one WorkbenchSchema by id
     * 
     * @param integer $id
     * 
     * @return mixed WorkbenchSchema or false on failure.
     */
    public function find($id) 
    {
        $result = $this->em->getRepository('MstDoctrineWorkbenchBundle:WorkbenchSchema')->find($id);
        
        return (null === $result) ? false : $result;
    }
    
    /**
     * Save a WorkbenchSchema
     * 
     * @param WorkbenchSchema $entity
     * 
     * @return bool
     */
    public function save(WorkbenchSchema $entity)
    {   
        $result = false;

        try {
            $this->em->persist($entity);
            $this->em->flush($entity);
            
            $result = true;
        } catch (\Exception $e) {
            
        }
        
        return $result;
    }
    
    /**
     * Remove a WorkbenchSchema by id
     * 
     * @param integer $id
     * 
     * @return bool
     */
    public function delete($id)
    {
        $result = false;
        
        try {
            $entity = $this->find($id);
            
            if (null !== $entity) {
                $this->em->remove($entity);
                $this->em->flush($entity);
            
                $result = true;
            }
        } catch (\Exception $e) {
            
        }
        
        return $result;
    }
}
