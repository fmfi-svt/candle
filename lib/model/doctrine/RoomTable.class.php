<?php

class RoomTable extends Doctrine_Table
{
    public function searchRoomsByName($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(' ', '%', $queryString).'%';

        $q = Doctrine_Query::create()
                ->from('Room r')
                ->where('r.name LIKE ?', $matchQueryString)
                ->orderBy('r.name')
                ->limit($limit);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
