<?php

class userActions extends sfActions {

    public function executeRegister(sfWebRequest $request) {
        $this->form = new RegistrationForm();
    }

    public function executeRegisterDo(sfWebRequest $request) {
        $this->setTemplate('register');
        $form = new RegistrationForm();
        $this->form = $form;
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            if ($form->getValue('password')!=$form->getValue('password_repeat')) {
                $this->getUser()->setFlash('error', 'Nové heslo sa nezhoduje s overením');
                return;
            }
            $user = new sfGuardUser();
            $user->setUsername($form->getValue('username'));
            $user->setPassword($form->getValue('password'));
            $user->setIsActive(true);
            try {
                $user->save();
            }
            catch (Doctrine_Connection_Exception $e) {
                if ($e->getPortableCode() == -5) {
                    $this->getUser()->setFlash('error', 'Takéto používateľské meno už existuje');
                    return;
                }
                throw $e;
            }
            $this->getUser()->signIn($user);
            return $this->redirect('@homepage');
        }
    }

    public function executeProfile(sfWebRequest $request) {
        $this->form = new ChangePasswordForm();
    }

    public function executeChangePassword(sfWebRequest $request) {
        $this->setTemplate('profile');
        $form = new ChangePasswordForm();
        $this->form = $form;
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            $user = $this->getUser()->getGuardUser();
            if ($user == null) throw new Exception('neprihlaseny');
            if (!$this->getUser()->checkPassword($form->getValue('old_password'))) {
                $this->getUser()->setFlash('error', 'Zlé staré heslo');
                return;
            }
            if ($form->getValue('password')!=$form->getValue('password_repeat')) {
                $this->getUser()->setFlash('error', 'Nové heslo sa nezhoduje s overením');
                return;
            }
            $user->setPassword($form->getValue('password'));
            $user->save();
            $this->getUser()->setFlash('notice','Heslo úspešne zmenené');
            return $this->redirect('@homepage');
        }
    }

}