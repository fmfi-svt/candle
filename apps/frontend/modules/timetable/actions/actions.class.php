<?php

class timetableActions extends sfActions {
    
    public function executeShow(sfWebRequest $request) {
        $manager = $this->getUser()->getTimetableManager();
        $id = $request->getParameter('id');
        $timetable = $manager->getTimetable($id);
        if (!$timetable) {
            throw new sfError404Exception();
        }
        $this->timetable = $timetable;
    }

}
