<?php

class TeacherTable extends Doctrine_Table
{

    public function searchTeachersByName($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(array(' ', '.'), '%', $queryString).'%';

        $q = Doctrine_Query::create()
                ->from('Teacher t')
                ->where('CONCAT(t.given_name,\' \',t.family_name) LIKE ?', $matchQueryString)
                ->orderBy('t.family_name')
                ->limit($limit);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
