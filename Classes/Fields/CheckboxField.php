<?php
namespace Cuisine\Fields;


class CheckboxField extends DefaultField{


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
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){

        $class = 'field-wrapper';

        $class .= ' '.$this->type;

        if( $this->properties['label'] )
            $class .= ' label-'.$this->properties['label'];

        echo '<div class="'.$class.'">';

            echo $this->build();

        echo '</div>';
    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){


        $html = '<input type="'.$this->type.'" ';

            $html .= 'id="'.$this->id.'" ';

            $html .= 'class="'.$this->getClass().'" ';

            $html .= 'name="'.$this->name.'" ';

            $html .= 'value="true" '; 

            if( $this->getValue() == true )
                $html .= ' checked';

        $html .= '/>';

        $html .= '<label for="'.$this->id.'">'.$this->label.'</label>';


        return $html;
    }




}