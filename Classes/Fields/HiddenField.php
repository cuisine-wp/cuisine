<?php
namespace Cuisine\Fields;


class HiddenField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'hidden';
    }


    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){
		
		echo $this->build();

    }


}