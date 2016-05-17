<?php
namespace Cuisine\Fields;


class SelectField extends ChoiceField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'select';
    }

   

    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $choices = $this->getChoices();
        $choices = $this->parseChoices( $choices );

        $html = '<select ';

            $html .= 'id="'.$this->id.'" ';

            $html .= 'class="'.$this->getClass().'" ';

            $html .= 'name="'.$this->name.'" ';

            $html .= $this->getValidation();

            if( $this->getProperty( 'multi' ) )
                $html .= ' multiple';

        $html .= '>';

        foreach( $choices as $choice ){

            $html .= $this->buildOption( $choice );

        }

        $html .= '</select>';

        return $html;
    
    }


    /**
     * Return html for an option
     * 
     * @param  array/string $choice
     * @return string HTML
     */
    public function buildOption( $choice ){
    
        //set choice variables:
        $value = $choice['key'];
        $label = ( isset( $choice['label'] ) ? $choice['label'] : false );

        $html = '<option value="'.$value.'"';

            $html .= ( $this->getValue() == $value ? ' selected' : '' );

        $html .= '>'.$label.'</option>';

        return $html;
    }




}