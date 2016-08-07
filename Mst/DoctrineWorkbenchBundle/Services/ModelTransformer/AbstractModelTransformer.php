<?php

namespace Mst\DoctrineWorkbenchBundle\Services\ModelTransformer;

use Mst\DoctrineWorkbenchBundle\Models\ModelTransformer;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * @author javi
 */
abstract class AbstractModelTransformer implements ModelTransformer
{   
    /** @var array */
    public static $_cascadeOptions = array(
        'isCascadePersist' => 'persist',
        'isCascadeRemove' => 'remove',
        'isCascadeDetach' => 'detach',
        'isCascadeMerge' => 'merge',
        'isCascadeRefresh' => 'refresh',
    );
            
    /**
     * Gets Doctrine relation type from UI relation type
     * 
     * @param int $type
     * 
     * @return int
     * 
     * @throws \Exception
     */
    protected function getRelationType($type, $entityDirection = null)
    {
        $types = array(
            1 => ClassMetadataInfo::ONE_TO_ONE,
            2 => array(
                'source' => ClassMetadataInfo::ONE_TO_MANY,
                'target' => ClassMetadataInfo::MANY_TO_ONE,
            ),
            3 => ClassMetadataInfo::MANY_TO_MANY,
        );
        
        if (!array_key_exists($type, $types)) {
            throw new \Exception('Invalid relation type.');
        }
        
        if (null !== $entityDirection && !in_array($entityDirection, array('source', 'target'))) {
            throw new \Exception('Invalid relation type OneToMany/ManyToOne.');
        }
        
        if (2 === $type && null !== $entityDirection) {
            return $types[$type][$entityDirection];
        }
        
        return $types[$type];
    }

    /**
     * Gets Doctrine generatotType from UI strategy
     * 
     * @param string $strategy
     * 
     * @return int
     */
    protected function getGeneratorType($strategy)
    {
        $types = array(
            'AUTO' => ClassMetadataInfo::GENERATOR_TYPE_AUTO
        );
        
        $result = ClassMetadataInfo::GENERATOR_TYPE_NONE;
        
        if (array_key_exists($strategy, $types)) {
            $result = $types[$strategy];
        }
        
        return $result;
    }
    
    /**
     * Get Doctrine cascade options from UI cascade options
     * 
     * @param array $options
     * 
     * @return array
     */
    protected function getCascadeOptions(array $options)
    {        
        $result = array();
        
        foreach (self::$_cascadeOptions as $key => $option) {
            $result[$key] = in_array($option, $options);
        }
        
        return $result;
    }
}
