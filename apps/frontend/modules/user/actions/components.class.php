<?php

class userComponents extends sfComponents {
    public function executeMenu(sfWebRequest $request) {
        $guardUser = $this->getUser()->getGuardUser();
        if ($guardUser) {
            $this->username = $guardUser->getUsername();
        }
        else {
            $this->username = null;
        }
    }
}
