<?php
namespace Cuisine\Fields;


class DateField extends DefaultField{

    /**
     * Custom classes
     * 
     * @var array
     */
    $classes = array( 'datepicker' );


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'text';
    }



}