<?php

/**

    Copyright 2010,2012 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */

class userActions extends sfActions {

    public function executeSignin(sfWebRequest $request)
    {
        $user = $this->getUser();
        if ($user->isAuthenticated()) {
            return $this->redirect('@homepage');
        }
        
        $server = $request->getPathInfoArray();
        
        if (!isset($server['REMOTE_USER'])) {
            return 'Failure';
        }
        
        $login = $server['REMOTE_USER'];
        if (strlen($login) == 0) {
            return 'Failure';
        }
        
        $dbUser = Doctrine::getTable('User')->findOneByLogin($login);
        if ($dbUser == null) {
            $dbUser = new User();
            $dbUser->setLogin($login);
            $dbUser->save();
        }

        $user->signIn($dbUser);
        return $this->redirect('@homepage');
    }
    
    public function executeSignout(sfWebRequest $request)
    {
        $this->getUser()->signOut();

        $cosignLogoutUrl = sfConfig::get('app_cosign_logout_url');
        $homepage = $this->generateUrl('homepage', array(), true);

        if ($cosignLogoutUrl === null || strlen($cosignLogoutUrl) == 0) {
            return $this->redirect($homepage);
        }

        return $this->redirect($cosignLogoutUrl . '?' . $homepage);
    }
    
    public function executeSecure(sfWebRequest $request)
    {
        $this->getResponse()->setStatusCode(403);
    }
    
    public function executeProfile(sfWebRequest $request)
    {
        
    }
}