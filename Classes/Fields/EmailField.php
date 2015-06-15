<?php
namespace Cuisine\Fields;


class EmailField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'email';
        $this->addValidation( 'email' );
    
    }


}