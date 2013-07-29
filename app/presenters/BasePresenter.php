<?php

use Nette\Application\UI\Form;



/**
 * Base presenter for all application presenters.
 *
 * @property callable $newListFormSubmitted
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public $userRepository;
    public $vehicleRepository;

    public function inject(TechnicalControl\UserRepository $userRepository, TechnicalControl\VehicleRepository $vehicleRepository)
    {
        $this->userRepository = $userRepository;
        $this->vehicleRepository = $vehicleRepository;
    }
    /**
    * Vrací jinak zformátované datum
    * @param string
    * @param string
    * @return string
    */
    public function changeDateFormat($date, $format)
    {   
        $date = strtotime($date);
        return StrFTime($format, $date);
    }
    
}
