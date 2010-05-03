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

}