<?php
use Nette\Mail\Message, 
    Nette\Utils\Strings;

class SendPresenter extends BasePresenter
{
    private $user;
    private $password;
    
    private function createNewPassword()
    {
        $this->user = $this->userRepository->findByName('admin');
        $this->password = Strings::random(20);
        $auth = new TechnicalControl\Authenticator($this->userRepository);
        $auth->setPassword($this->user->id, $this->password);
    }
    
    public function actionMail()
    {   
        $this->createNewPassword();
        
        
        $mail = new Message;
        $mail->setFrom('Technické kontroly <example@email.cz>')
            ->addTo($this->user->email)
            ->setSubject('Nové heslo')
            ->setBody("Dobrý den,\nvaše vaše náhradní heslo pro přístup do aplikace Technické kontroly je: "
                            .$this->password."\npo přihlášení si jej prosím změňte.")
            ->send();
        $mailer = new Nette\Mail\SmtpMailer(array(
                'host' => 'smtp.seznam.cz',
                'username' => 'example@email.cz',
                'password' => '********',
                'secure' => 'ssl',
        ));
        $mail->setMailer($mailer);
        $mail->send();
        
        $this->flashMessage('Email byl odeslán na váš email.');
        $this->redirect('Sign:in');
    }
    
    
}
