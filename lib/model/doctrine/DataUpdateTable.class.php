<?php

class DataUpdateTable extends Doctrine_Table
{

    function getLastUpdate($hydration = Doctrine::HYDRATE_ARRAY) {
        $q = Doctrine_Query::create()
                ->from('DataUpdate du')
                ->andWhere('du.datetime = ( SELECT MAX(datetime) from DataUpdate )');
        
        return $q->fetchOne(array(), $hydration);
    }

}
