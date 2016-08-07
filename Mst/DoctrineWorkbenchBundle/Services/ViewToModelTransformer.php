<?php

namespace Mst\DoctrineWorkbenchBundle\Services;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\Export\ClassMetadataExporter;

/**
 * @author javi
 */
class ViewToModelTransformer
{    
    /** @var array */
    public static $_modelsTransformers = array(
        'annotation' => 'Mst\DoctrineWorkbenchBundle\Services\ModelTransformer\AnnotationModelTransformer',
        // Not implemented yet
        //'yaml' => 'Mst\DoctrineWorkbenchBundle\Services\ModelTransformer\YamlModelTransformer',
        //'yml' => 'Mst\DoctrineWorkbenchBundle\Services\ModelTransformer\YamlModelTransformer',
    );
    
    /**
     * Map entities from json to models
     * 
     * @param string $jsonData
     * @param string $type
     * @param string $dest
     * 
     * @return void 
     * 
     * @throws ExportException
     * @throws InvalidArgumentException
     */
    public function handleJsonData($jsonData, $type, $dest)
    {
        $parsedData = $this->jsonDecode($jsonData);
        $parsedDataArray = $this->parseJsonDataToArray($parsedData->entities, $parsedData->relations, $type);
        $metadatas = $this->parseDataArrayToMetadatas($parsedDataArray);

        $exporter = $this->getExporter($type, $dest);
        $exporter->setMetadata($metadatas);
        $exporter->export();
    }
    
    /**
     * Parse stdObj entities from UI to an array of ClassMetadataInfo
     * 
     * @param array $entities
     * @param array $relations
     * @param string $type
     * 
     * @return array
     * 
     * @throws \InvalidArgumentException
     */
    protected function parseJsonDataToArray(array $entities, array $relations, $type)
    {
        $result = array();
        $transformer = $this->getModelTransformer($type);
        $associationMappings = $transformer->generateAssociationMappings($entities, $relations);
        
        foreach ($entities as $entity) {
            $fieldsMappings = $transformer->generateFieldsMappings($entity->fields);
            $result[$entity->id] = array(
                'entityName' => $entity->entityName,
                'tableName' => array('name' => $entity->tableName),
                'namespace' => $entity->namespace,
                'fieldMappings' => $fieldsMappings['fieldMappings'],
                'fieldNames' => $fieldsMappings['fieldNames'],
                'columnNames' => $fieldsMappings['columnNames'],
                'generatorType' => $fieldsMappings['generatorType'],
                'identifier' => $fieldsMappings['identifier'],
                'associationMappings' => array(),
            );
            
            foreach ($entity->relations as $relation) {
                $result[$entity->id]['associationMappings'][] = $associationMappings[$relation][$entity->id];
            }
        }
        
        return $result;
    }
    
    /**
     * Parse array data to an array of ClassMetadataInfo
     * 
     * @param array $data
     * 
     * @return ClassMetadataInfo
     */
    protected function parseDataArrayToMetadatas(array $data)
    {
        $result = array();
        foreach ($data as $entity) {
            $class = new ClassMetadataInfo($entity['namespace'].'\\'.$entity['entityName']);

            $class->table = $entity['tableName'];
            $class->fieldMappings = $entity['fieldMappings'];
            $class->fieldNames = $entity['fieldNames'];
            $class->columnNames = $entity['columnNames'];
            $class->identifier = $entity['identifier'];
            $class->generatorType = $entity['generatorType'];
            $class->associationMappings = $entity['associationMappings'];
            
            $result[] = $class;
        }
        
        return $result;
    }

    /**
     * Gets an exporter driver instance.
     *
     * @param string      $type The type to get (annotation, yaml, yml, xml, php). Actually only annotation is supported.
     * @param string|null $dest The directory where the exporter will export to.
     *
     * @return Driver\AbstractExporter
     *
     * @throws ExportException
     */
    protected function getExporter($type, $dest = null)
    {
        if (!array_key_exists($type, self::$_modelsTransformers)) {
            throw new \InvalidArgumentException("Unsuported Exporter type: '$type'.");
        }
        
        $cme = new ClassMetadataExporter();
        $exporter = $cme->getExporter($type, $dest);
        $exporter->setOverwriteExistingFiles(true);
        
        if ('annotation' === $type) {
            $entityGenerator = new EntityGenerator();
            $entityGenerator->setGenerateAnnotations(false);
            $entityGenerator->setGenerateStubMethods(true);
            $entityGenerator->setRegenerateEntityIfExists(false);
            $entityGenerator->setUpdateEntityIfExists(true);
            $entityGenerator->setNumSpaces(4);
            $entityGenerator->setAnnotationPrefix('ORM\\');
            
            $exporter->setEntityGenerator($entityGenerator);
        }
        
        return $exporter;
    }
    
    /**
     * Get a model transformer instance
     * 
     * @param type $type The type to get (annotation, yml)
     * 
     * @return ModelTransformer
     * 
     * @throws \InvalidArgumentException
     */
    protected function getModelTransformer($type)
    {
        if ( ! isset(self::$_modelsTransformers[$type])) {
            throw new \InvalidArgumentException("Unsuported ModelTransformer type: '$type'.");
        }

        $class = self::$_modelsTransformers[$type];

        return new $class();
    }
    
    /**
     * Converts a json string to a stdObj
     * 
     * @param string $data
     * 
     * @return stdObj
     */
    protected function jsonDecode($data)
    {
        return json_decode($data);
    }
}