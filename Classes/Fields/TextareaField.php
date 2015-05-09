<?php
namespace Cuisine\Fields;


class TextareaField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'textarea';
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

        $html = '<textarea ';

            $html .= 'id="'.$this->id.'" ';

            $html .= 'class="'.$this->getClass().'" ';

            $html .= $this->getPlaceholder();

            $html .= $this->getValidation();

        $html .= '>';

            $html .= $this->getDefault();

        $html .= '</textarea>';

        return $html;
    }




}