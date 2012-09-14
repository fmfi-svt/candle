<?php

class AutoSluggableRoute extends sfDoctrineRoute {
    
    protected function doConvertObjectToArray($object) {
        $parameterName = $this->getParameterName();
        $columns = array_merge($this->options['columns'], array('id'));
        $slug = '';
        $first = true;
        foreach ($columns as $column) {
            if (!$first) $slug .= '-';
            
            $value = $object[$column];
            if ($value !== null) {
                $slug .= $value;
                $first = false;
            }
        }
        $slugifier = new Slugifier();
        $slug = $slugifier->slugify($slug);
        $parameters = array();
        if ($slug !== null && preg_match('/^[a-zA-Z0-9-]+$/', $slug)) {
            $parameters[$parameterName] = $slug;
        }
        else {
            $parameters[$parameterName] = $object['id'];
        }
        return $parameters;
    }

    protected function getObjectsForParameters($parameters) {
        $tableModel = Doctrine_Core::getTable($this->options['model']);
        $matchColumns = (isset($this->options['matchColumns']) ? $this->options['matchColumns'] : array());
        $parameterName = $this->getParameterName();
        $slug = $parameters[$parameterName];
        $matches = array();
        if (preg_match('/(?:^|-)(\d+)$/', $slug, $matches)) {
            $results = $tableModel->find($matches[1]);
        }
        else {
            $results = null;
            $found = false;
            foreach ($matchColumns as $column) {
                $results =  $tableModel->findBy($column, $slug);
                if ($results instanceof Doctrine_Record || count($results) > 0) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $results = new Doctrine_Collection($tableModel);
            }
        }
        
        if ($results instanceof Doctrine_Record)
        {
            $obj = $results;
            $results = new Doctrine_Collection($obj->getTable());
            $results[] = $obj;
        }
        
        return $results;
    }

    protected function getParameterName() {
        if (!isset($this->options['parameter'])) return 'slug';
        return $this->options['parameter'];
    }
    
}