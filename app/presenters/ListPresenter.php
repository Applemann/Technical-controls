<?php
use Nette\Application\UI;

class ListPresenter extends BasePresenter
{
    private $changeValues = array();
    

    protected function startup()
    {
        
        parent::startup();
        if (!$this->getUser()->isLoggedIn()){
            $this->redirect('Sign:in');
        }

    }

    protected function createComponentAddVehicle()
    {
    
        $form = new UI\Form;
        $form->addText('name')
        ->setRequired('Vložte jméno vozidla');
        
        $form->addText('license_plate')
        ->setRequired('Vložte registrační značku');
        
        $form->addText('control_date')
        ->setRequired('Vložte datum kontroly');
        
        $form->addSubmit('send', 'Přidat');
        
        $form->onSuccess[] = $this->addVehicle;
        return $form;
    
    }
    
    public function addVehicle($form)
    {
        $this->vehicleRepository->getDatabase()->exec('INSERT INTO vehicle', $form->getValues());
        $this->flashMessage('Vozidlo bylo přidáno.');
        $this->redirect('List:');
    }
    
    protected function createComponentChangeVehicle()
    {
        
        $form = new UI\Form;
        $form->addHidden('id');
        
        $form->addText('name');
        
        $form->addText('license_plate');
        
        $form->addText('control_date');
        
        $form->addSubmit('send', 'Uložit');
        
        $form->setDefaults($this->changeValues);
        
        $form->onSuccess[] = $this->changeVehicle;
        return $form;
    
    }
    
    public function changeVehicle($form)
    {
        $id = $form->getValues()['id'];
        unset($form->getValues()['id']);
        $this->vehicleRepository->findById($id)->update($form->getValues());
        $this->flashMessage('Vozidlo bylo uloženo.');
        $this->redirect('List:');
    }
    
    public function actionDelete($id)
    {
        $this->vehicleRepository->findBy(array('id' => $id))->delete();
        $this->flashMessage('Vozidlo bylo odebráno.');
        $this->redirect('List:');
    }
    

    public function renderDefault($changeId=Null)
    {
        if ($changeId !== Null)
        {
            $this->changeValues = $this->vehicleRepository->findById($changeId)->toArray();
            $date = $this->changeValues['control_date'];
            $this->changeValues['control_date'] = $this->changeDateFormat($date, "%Y-%m-%d");
            
        }
            $this->template->listVehicles = $this->vehicleRepository->findAll();
            $this->template->change = $changeId;
        
    }
    
    

}
