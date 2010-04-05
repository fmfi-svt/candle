<?php

class LessonTable extends Doctrine_Table
{
    public function getLessons(Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()
                ->from('Lesson l');
        }
        
        return $q->execute();
    }
    
    public function getLessonById($id) {
        $q = Doctrine_Query::create()
            ->from('Lesson l')
            ->andWhere('l.id = ?', $id);
        return $q->fetchOne();
    }
}
