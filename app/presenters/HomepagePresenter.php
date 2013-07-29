<?php


class HomepagePresenter extends BasePresenter
{
    private $login;
    private $messages = array();
    private $vehicle, $date, $id, $count;
    
    public function beforeRender()
    {
        $count = $this->vehicleRepository->findAll()->count();
        for ($this->id=0; $this->id<=$count+10; $this->id++)
        {
            $this->compareDateIfVehicleExists();
        }
        if (sizeof($this->messages) == 0)
            array_push($this->messages, "Zatím nejsou žádná důležitá hlášení.");
    }
    
    private function compareDateIfVehicleExists()
    {
        if ( $this->vehicleRepository->findAll()->offsetExists($this->id) )
        {
            $this->vehicle = $this->vehicleRepository->findById($this->id)->toArray();
            $this->compareYear();
        }else{
            $this->count++;
        }
    }
        
    private function compareDate($part, $addPartDate=0, $addNowPartDate=0)
    {
        $partDate = $this->changeDateFormat($this->vehicle['control_date'], '%'.$part);
        $partDate = intval($partDate) + ($addPartDate);
        $nowPartDate = intval(date($part)) + ($addNowPartDate);
        
        if($part == 'm' and $partDate == 1) 
            $partDate = 12;
        if ($partDate == $nowPartDate)
            return 1;
        if ($partDate > $nowPartDate)
            return 2;
        if ($partDate < $nowPartDate)
            return 3;
    }
    
    private function compareYear()
    {
        if($this->compareDate('Y') == 1)
        {
            $this->compareMonth();
        }
        if($this->compareDate('Y', -1) == 1)
        {
            $this->compareMonth();
        }
    }
        
    private function compareMonth()
    {
        if($this->compareDate('m', -1) == 1)
        {
            $this->createMessage();
        }
        
        if($this->compareDate('m') == 1)
        {
            $this->compareDay();
        }
    }
    
    private function compareDay()
    {      
        if($this->compareDate('d') == 2)
        {
            $this->createMessage();
        }
    }
        
    private function createMessage()
    {
        $this->date = $this->changeDateFormat($this->vehicle['control_date'], "%d.%m.%Y");
        array_push($this->messages, "Do dne ".$this->date.", musí být ".$this->vehicle['name'].
        " s poznávací značkou ".$this->vehicle['license_plate']." na technické kontrole.");
    }
    
    
    public function renderDefault()
    {
        
        $this->template->messages = $this->messages;
        $this->template->login = $this->getUser()->isLoggedIn();
    }

}
