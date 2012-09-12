<?php

class ColumnOrIdRoute extends sfDoctrineRoute {
    
    protected function doConvertObjectToArray($object) {
        $column = $this->options['column'];
        $parameterName = $column . '_or_id';
        $value = $object[$column];
        $parameters = array();
        if ($value !== null && preg_match('/^[a-zA-Z0-9-]+$/', $value)) {
            $parameters[$parameterName] = $value;
        }
        else {
            $parameters[$parameterName] = $object['id'];
        }
        return $parameters;
    }

    protected function getObjectsForParameters($parameters) {
        $tableModel = Doctrine_Core::getTable($this->options['model']);
        $column = $this->options['column'];
        $parameterName = $column . '_or_id';
        $columnOrId = $parameters[$parameterName];
        if (preg_match('/^\d+$/', $columnOrId)) {
            return $tableModel->find($columnOrId);
        }
        $results =  $tableModel->findBy($column, $columnOrId);
        
        if ($results instanceof Doctrine_Record)
        {
            $obj = $results;
            $results = new Doctrine_Collection($obj->getTable());
            $results[] = $obj;
        }
        
        return $results;
    }

    
}