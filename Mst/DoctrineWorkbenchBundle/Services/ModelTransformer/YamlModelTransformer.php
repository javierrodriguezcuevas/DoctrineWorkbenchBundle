<?php

namespace Mst\DoctrineWorkbenchBundle\Services\ModelTransformer;

use Mst\DoctrineWorkbenchBundle\Models\ModelTransformer;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * @author javi
 */
class YamlModelTransformer extends AbstractModelTransformer implements ModelTransformer
{       
    /**
     * Parse stdObj fields from UI to an array with fieldMappings
     * 
     * @param array $fields
     * 
     * @return array
     */
    public function generateFieldsMappings(array $fields)
    {
        $result = array(
            'generatorType' => ClassMetadataInfo::GENERATOR_TYPE_NONE,
            'identifier' => array(),
        );
        
        foreach ($fields as $field) {
            if (sizeof($field->relations) === 0) {
                $fm = array(
                    'fieldName' => $field->name,
                    'columnName' => $field->tableName,
                    'type' => $field->type
                );

                $result['fieldNames'][$field->tableName] = $field->name;
                $result['columnNames'][$field->name] = $field->tableName;

                if ($field->pk) {
                    $result['generatorType'] = $this->getGeneratorType($field->strategy);
                    $fm['id'] = true;
                } else {
                    $fm['nullable'] = $field->nn;
                    $fm['length'] = $field->length;
                }

                $result['fieldMappings'][] = $fm;
            } else {
                $result['identifier'] = array($field->name);
            }
        }
        
        return $result;
    }

    /**
     * Parse stdObj relations from UI to an array with associationMappings
     * 
     * @param array $entities
     * @param array $relations
     * 
     * @return array
     */
    public function generateAssociationMappings(array $entities, array $relations) 
    {
        $associationMappings = array();
        
        $entitiesNames = array();
        $fieldsNames = array();
        foreach ($entities as $entity) {
            $entitiesNames[$entity->id] = $entity->entityName;
            
            foreach ($entity->fields as $field) {
                $fieldsNames[$field->id] = $field->name;
            }
        }
        
        foreach ($relations as $relation) {
            $associationMappings[$relation->connectionId][$relation->sourceEntityId] = array(
                'fieldName' => $relation->sourceField->name,
                'targetEntity' => $entity->namespace.'\\'.$entitiesNames[$relation->targetEntityId],
                'mappedBy' => $fieldsNames[$relation->targetField->id],
                'cascade' => $this->getCascadeOptions($relation->cascadeOptions),
                'type' => $this->getRelationType($relation->type, 'source'),
                'orphanRemoval' => false,
            );
            
            $associationMappings[$relation->connectionId][$relation->targetEntityId] = array(
                'fieldName' => $relation->targetField->name,
                'targetEntity' => $entity->namespace.'\\'.$entitiesNames[$relation->sourceEntityId],
                'inversedBy' => $fieldsNames[$relation->sourceField->id],
                'cascade' => $this->getCascadeOptions($relation->cascadeOptions),
                'type' => $this->getRelationType($relation->type, 'target'),
                'orphanRemoval' => false,
                'joinColumns' => array(array(
                    'name' => $relation->targetField->tableName,
                    'referencedColumnName' => $fieldsNames[$relation->targetRelatedFieldId]
                )),
            );
            
            // fix cascade options
            $associationMappings[$relation->connectionId][$relation->sourceEntityId] = array_merge(
                $associationMappings[$relation->connectionId][$relation->sourceEntityId],
                $this->getCascadeOptions($relation->cascadeOptions)
            );
            $associationMappings[$relation->connectionId][$relation->targetEntityId] = array_merge(
                $associationMappings[$relation->connectionId][$relation->targetEntityId],
                $this->getCascadeOptions($relation->cascadeOptions)
            );
            
            if (1 === $relation->type) {
                $associationMappings[$relation->connectionId][$relation->sourceEntityId]['inversedBy'] = $fieldsNames[$relation->sourceField->id];
                $associationMappings[$relation->connectionId][$relation->targetEntityId]['mappedBy'] = $fieldsNames[$relation->targetField->id];
                $associationMappings[$relation->connectionId][$relation->sourceEntityId]['joinColumns'] = array(array(
                    'name' => $relation->targetField->tableName,
                    'referencedColumnName' => $fieldsNames[$relation->targetRelatedFieldId]
                ));
            }
            if (2 === $relation->type || 3 === $relation->type) {
                $associationMappings[$relation->connectionId][$relation->sourceEntityId]['inversedBy'] = $fieldsNames[$relation->sourceField->id];
                $associationMappings[$relation->connectionId][$relation->targetEntityId]['mappedBy'] = $fieldsNames[$relation->targetField->id];
            }
            
            if (3 === $relation->type) {
                unset($associationMappings[$relation->connectionId][$relation->targetEntityId]['joinColumns']);
                $associationMappings[$relation->connectionId][$relation->sourceEntityId]['joinTable'] = array(
                    'name' => $relation->tableName,
                    'joinColumns' => array(
                        array(
                            'name' => $relation->targetField->tableName,
                            'referencedColumnName' => $fieldsNames[$relation->targetRelatedFieldId]
                        )
                    ),
                    'inverseJoinColumns' => array(
                        array(
                            'name' => $relation->sourceField->tableName,
                            'referencedColumnName' => $fieldsNames[$relation->sourceRelatedFieldId]
                        )
                    )
                );
            }
        }
        
        return $associationMappings;
    }
}
