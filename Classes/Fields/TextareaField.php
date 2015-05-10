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

            if( $this->properties['defaultValue'] )
                $html .= $this->properties['defaultValue'];

        $html .= '</textarea>';

        return $html;
    }




}