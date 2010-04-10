<?php

class LessonTable extends Doctrine_Table
{

    function listBySubjectId($subjectId) {
        $q = Doctrine_Query::create()
                ->from('Lesson l')
                ->andWhere('l.subject_id = ?', $subjectId);
        return $q->execute();
    }

}
