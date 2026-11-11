<?php

/** Archive requests may browse data and edit anonymous sessions, but never write to the database. */
class CandleArchiveFilter extends sfFilter
{
    public function execute($filterChain)
    {
        if (CandleArchive::isActive()) {
            $allowed = array(
                'timetable' => array('homepage', 'show', 'new', 'create', 'rename', 'renameExecute',
                    'duplicate', 'lessonAction', 'changeLessons', 'deleteAsk', 'delete', 'import', 'importDo', 'export'),
                'room' => array('list', 'show', 'export', 'duplicate'),
                'studentGroup' => array('list', 'show', 'export', 'duplicate'),
                'timetableTeacher' => array('list', 'show', 'export', 'duplicate'),
                'timetablePublished' => array('show', 'export', 'duplicate'),
                'lesson' => array('index', 'query', 'exportAll'),
                'panel' => array('listLessons', 'listTeachers', 'listRooms', 'listStudentGroups'),
                'freeRoom' => array('params', 'search', 'current'),
                'lessonSearch' => array('params', 'search', 'current'),
                'about' => array('termsOfUse'),
            );
            $module = $this->context->getModuleName();
            $action = $this->context->getActionName();
            $request = $this->context->getRequest();
            if ($module === sfConfig::get('sf_error_404_module') && $action === sfConfig::get('sf_error_404_action')) {
                // Error forwards retain the original format (including rejected ICS exports).
                $request->setRequestFormat('html');
            }
            elseif (!isset($allowed[$module]) || !in_array($action, $allowed[$module], true)
                || $request->getRequestFormat() === 'ics') {
                throw new sfError404Exception('This action is unavailable in archive mode.');
            }
        }
        $filterChain->execute();
    }
}
