<?php

use Nette\Application\UI;



class SignPresenter extends BasePresenter
{
    
protected function createComponentSignInForm()
{
    
        $form = new UI\Form;
        $form->addText('username', 'Username:')
        ->setRequired('Please enter your username.');
        
        $form->addPassword('password', 'Password:')
        ->setRequired('Please enter your password.');
        
        $form->addSubmit('send', 'Přihlásit se');
        
        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
    
}

public function signInFormSucceeded($form)
{
    $values = $form->getValues();
    $this->getUser()->setExpiration('+ 20 minutes', TRUE);
    
    
    try {
        $this->getUser()->login($values->username, $values->password);
    } catch (Nette\Security\AuthenticationException $e) {
        $form->addError($e->getMessage());
        return;
    }
    
    $this->redirect('List:');
}


public function actionOut()
{
    $this->getUser()->logout();
    $this->flashMessage('Byl jste odhlášen');
    $this->redirect('in');
}

}
