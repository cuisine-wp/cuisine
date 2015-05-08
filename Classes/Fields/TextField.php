<?php
namespace Cuisine\Field\Fields;


class TextField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'text';
    }

    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){

        echo $this->getLabel();
        echo $this->build();

    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $html = '<input type="text" ';

            $html .= 'id="'.$this->id.'" ';

            $html .= 'class="'.$this->getClass().'" ';

            $html .= $this->getDefault();

            $html .= $this->getPlaceholder();

            $html .= $this->getValidation();

        $html .= '/>';

        return $html;
    }




}