<?php
namespace Cuisine\Fields;


class CheckboxesField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'checkbox';
    }



    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $choices = $this->getChoices();


        return $html;
    }




}