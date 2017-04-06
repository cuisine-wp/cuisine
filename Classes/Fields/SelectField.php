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

            if( $this->getProperty( 'multiple' ) )
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

            $html .= $this->selected( $value );

        $html .= '>'.$label.'</option>';

        return $html;
    }


    /**
     * Check if a value is selected
     * 
     * @return string
     */
    public function selected( $value )
    {
        $values = $this->getValue();
        if( !is_array( $values ) )
            $values = [ $values ];

        return ( in_array( $value, $values ) ? ' selected' : '' ); 

    }




}