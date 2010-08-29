<?php

class layoutComponents extends sfComponents {
    public function executeLastUpdate(sfWebRequest $request) {
        $this->lastUpdate = Doctrine::getTable('DataUpdate')->getLastUpdate();
    }
}