<?php

namespace Mst\DoctrineWorkbenchBundle\Services;

use JsonSchema\Validator;

/**
 * @author javi
 */
class ViewDataValidator 
{   
    const VALID_JSON = '{"$schema": "http://json-schema.org/draft-04/schema#","type": "object","properties": {"id": {"type": "integer"}},"required": ["id"]}';
    const VALID_SAVE_DATA = '{ "$schema": "http://json-schema.org/draft-04/schema#", "type": "object", "properties": { "name": { "type": "string" }, "zoom": { "type": "integer" }, "schema": { "type": "object", "properties": { "entities": { "type": "array", "items": { "type": "object", "properties": { "x": { "type": "integer" }, "y": { "type": "integer" }, "fields": { "type": "array", "items": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] } }, "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "entityName": { "type": "string" }, "tableName": { "type": "string" }, "namespace": { "type": "string" } }, "required": [ "x", "y", "fields", "relations", "id", "entityName", "tableName", "namespace" ] } }, "relations": { "type": "array", "items": { "type": "object", "properties": { "connectionId": { "type": "string" }, "name": { "type": "string" }, "type": { "type": "integer" }, "hoverClass": { "type": "string" }, "cascadeOptions": { "type": "array", "items": {} }, "sourceEntityId": { "type": "string" }, "targetEntityId": { "type": "string" }, "sourceFieldId": { "type": "string" }, "targetFieldId": { "type": "string" }, "sourceField": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] }, "targetField": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] }, "targetRelatedFieldId": { "type": "string" }, "tableName": { "type": "string" }, "sourceRelatedFieldId": { "type": "string" } }, "required": [ "connectionId", "name", "type", "hoverClass", "cascadeOptions", "sourceEntityId", "targetEntityId", "sourceFieldId", "targetFieldId", "sourceField", "targetField", "targetRelatedFieldId", "tableName", "sourceRelatedFieldId" ] } } }, "required": [ "entities", "relations" ] } }, "required": [ "name", "zoom", "schema" ] }';
    const VALID_PROCCESS_DATA = '{ "$schema": "http://json-schema.org/draft-04/schema#", "type": "object", "properties": { "entities": { "type": "array", "items": { "type": "object", "properties": { "x": { "type": "integer" }, "y": { "type": "integer" }, "fields": { "type": "array", "items": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] } }, "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "entityName": { "type": "string" }, "tableName": { "type": "string" }, "namespace": { "type": "string" } }, "required": [ "x", "y", "fields", "relations", "id", "entityName", "tableName", "namespace" ] } }, "relations": { "type": "array", "items": { "type": "object", "properties": { "connectionId": { "type": "string" }, "name": { "type": "string" }, "type": { "type": "integer" }, "hoverClass": { "type": "string" }, "cascadeOptions": { "type": "array", "items": {} }, "sourceEntityId": { "type": "string" }, "targetEntityId": { "type": "string" }, "sourceFieldId": { "type": "string" }, "targetFieldId": { "type": "string" }, "sourceField": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] }, "targetField": { "type": "object", "properties": { "relations": { "type": "array", "items": { "type": "string" } }, "id": { "type": "string" }, "name": { "type": "string" }, "tableName": { "type": "string" }, "length": { "type": "integer" }, "pk": { "type": "boolean" }, "nn": { "type": "boolean" }, "type": { "type": "string" }, "default": { "type": "null" } }, "required": [ "relations", "id", "name", "tableName", "length", "pk", "nn", "type", "default" ] }, "targetRelatedFieldId": { "type": "string" }, "tableName": { "type": "string" }, "sourceRelatedFieldId": { "type": "string" } }, "required": [ "connectionId", "name", "type", "hoverClass", "cascadeOptions", "sourceEntityId", "targetEntityId", "sourceFieldId", "targetFieldId", "sourceField", "targetField", "targetRelatedFieldId", "tableName", "sourceRelatedFieldId" ] } } }, "required": [ "entities", "relations" ] }';
    
    /**
     * Validate request content format
     * 
     * @param string $data
     * 
     * @return bool
     */
    public function isValidJson($data)
    {   
        return $this->validateSchema($data, self::VALID_JSON);
    }

    /**
     * Validate request content schema
     * 
     * @param string $data
     * 
     * @return bool
     */
    public function isValidSaveData($data) 
    {
        return $this->validateSchema($data, self::VALID_SAVE_DATA);
    }
    
    /**
     * Validade request content schema to be proccessed
     * 
     * @param string $data
     * 
     * @return bool
     */
    public function isValidProccessData($data) 
    {
        return $this->validateSchema($data, self::VALID_PROCCESS_DATA);
    }
    
    /**
     * Validate json against schema
     * 
     * @param string $data
     * 
     * @return bool
     */
    public function validateSchema($data, $schema)
    {
        if (!is_string($data) || empty($data) || !is_string($schema) || empty($schema)) {
            return false;
        }
                
        $validator = new Validator();
        $validator->check($data, $schema);
        
        return $validator->isValid();
    }
}
