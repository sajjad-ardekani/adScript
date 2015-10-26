<?php
namespace AppBundle\Form;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

class CreateVehicleFlow extends FormFlow {

    public function getName() {
        return 'createVehicle';
    }

    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'Dec',
                'form_type' => new AdFormType(),
            ),
            array(
                'label' => 'upload',
                'form_type' => new CreateVehicleStep2Form()
            ),
            array(
                'label' => 'finish',
            ),
        );
    }

}