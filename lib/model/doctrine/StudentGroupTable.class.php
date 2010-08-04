<?php

class StudentGroupTable extends Doctrine_Table
{

    public function searchStudentGroupsByName($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(' ', '%', $queryString).'%';

        $q = Doctrine_Query::create()
                ->from('StudentGroup s')
                ->where('s.name LIKE ?', $matchQueryString)
                ->orderBy('s.name')
                ->limit($limit);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
