<?php

/**
 * lesson actions.
 *
 * @package    candle
 * @subpackage lesson
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class lessonActions extends sfActions
{
    public function executeIndex(sfWebRequest $request) {
        $this->subjects = Doctrine::getTable('Subject')->getActiveSubjects();
    }
  
    public function executeQuery(sfWebRequest $request) {
        $searchText = $request->getParameter('q');
        if (!$searchText) {
            $this->getUser()->setFlash('error','Treba vyplniť hľadaný text');
        }
        $this->subjects = Doctrine::getTable('Subject')->searchSubjectsByAll($searchText);
    }
  
}
