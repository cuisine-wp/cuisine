<?php
namespace Cuisine\Fields;


class EditorField extends DefaultField{



    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'editor';
    }

    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){



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