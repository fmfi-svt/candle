<?php

class ColumnOrIdRoute extends sfDoctrineRoute {
    
    protected function doConvertObjectToArray($object) {
        $column = $this->getColumns();
        $parameterName = $this->getParameterName();
        $value = $object[$column[0]];
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
        $columns = $this->getColumns();
        $parameterName = $this->getParameterName();
        $parameter = $parameters[$parameterName];
        if (preg_match('/^\d+$/', $parameter)) {
            return $tableModel->find($parameter);
        }
        
        $results = null;
        $found = false;
        foreach ($columns as $column) {
            $results =  $tableModel->findBy($column, $parameter);
            if ($results instanceof Doctrine_Record || count($results) > 0) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $results = new Doctrine_Collection($tableModel);
        }
        
        if ($results instanceof Doctrine_Record)
        {
            $obj = $results;
            $results = new Doctrine_Collection($obj->getTable());
            $results[] = $obj;
        }
        
        return $results;
    }
    
    private function getColumns() {
        $columns = $this->options['column'];
        if (!is_array($columns)) {
            return array($columns);
        }
        return $columns;
    }
    
    private function getParameterName() {
        return implode('_or_', $this->getColumns()) . '_or_id';
    }

    
}