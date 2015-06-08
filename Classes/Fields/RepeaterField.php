<?php
namespace Cuisine\Fields;


class RepeaterField extends DefaultField{

    /**
     * Array that holds all fields
     * 
     * @var array
     */
    var $fields = array();

    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'repeater';
    }

    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){

        $this->fields = $this->properties['fields'];
        $prefix = $this->name.'[0]';

        foreach( $this->fields as $field ){

            $newName = $prefix.'['.$field->name.']';
            $field->setName( $newName );

            $field->render();

        }
      
    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $html = '';
        
        return $html;
    }




}