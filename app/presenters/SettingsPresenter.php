<?php

use Nette\Application\UI;


class SettingsPresenter extends BasePresenter
{
    
    protected function startup()
    {
        
        parent::startup();
        if (!$this->getUser()->isLoggedIn()){
            $this->redirect('Sign:in');
        }

    }

    protected function createComponentChangePassword()
    {
    
        $form = new UI\Form;
        $form->addPassword('old_password', 'Staré heslo:')
        ->setRequired('Zadejte staré heslo');
        
        $form->addPassword('new_password1', 'Nové heslo:')
        ->setRequired('Zadejte nové heslo');
        $form->addPassword('new_password2', 'Znovu nové heslo:')
        ->setRequired('Zadejte nové heslo ještě jednou pro kontrolu')
        ->addRule(UI\Form::EQUAL, 'Hesla se neshodují', $form['new_password1']);
        
        $form->addSubmit('send', 'Změnit');
        
        $form->onSuccess[] = $this->changePassword;
        return $form;
    
    }


    public function changePassword($form)
    {
        $values = $form->getValues();
        $auth = new TechnicalControl\Authenticator($this->userRepository);
        
        $user = $this->userRepository->findById($this->getUser()->getId())->toArray();
        if ($user['password'] === $auth->calculateHash($values->old_password, $user['password']))
        {
            $auth->setPassword($user['id'], $values->new_password1);
            $this->flashMessage('Heslo bylo změněno');
            
        }else{
            $this->flashMessage('Špatné heslo');
        }
        
        $this->redirect('Settings:');
    }

    protected function createComponentChangeEmail()
    {
    
        $form = new UI\Form;
        $form->addText('old_email', 'Starý email:')
        ->setRequired('Zadejte starý email');
        
        $form->addText('new_email1', 'Nový email:')
        ->setRequired('Zadejte nový email');
        $form->addText('new_email2', 'Znovu nový email:')
        ->setRequired('Zadejte nový email ještě jednou pro kontrolu')
        ->addRule(UI\Form::EQUAL, 'Emaily se neshodují', $form['new_email1']);
        
        $form->addSubmit('send', 'Změnit');
        
        $form->onSuccess[] = $this->changeEmail;
        return $form;
    
    }


    public function changeEmail($form)
    {
        $values = $form->getValues();
        $auth = new TechnicalControl\Authenticator($this->userRepository);
        
        $user = $this->userRepository->findById($this->getUser()->getId())->toArray();
        if ($user['email'] === $values->old_email)
        {
            $auth->setEmail($user['id'], $values->new_email1);
            $this->flashMessage('Email byl změněn');
            
        }else{
            $this->flashMessage('Špatně zadaný starý email');
        }
        
        $this->redirect('Settings:');
    }


}
