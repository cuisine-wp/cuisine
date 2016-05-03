<?php
namespace Cuisine\Fields;


class CheckboxesField extends ChoiceField{


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

        $class .= ' checkboxes';

        if( $this->properties['label'] )
            $class .= ' label-'.$this->properties['label'];

        if( $this->properties['wrapper-class'] && is_array( $this->properties['wrapper-class'] ) )
            $class .= ' '.implode( ' ', $this->properties['wrapper-class'] );


        echo '<div class="'.$class.'">';

            echo $this->getLabel();
            echo $this->build();

        echo '</div>';
    }

    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $html = '';
        $choices = $this->getChoices();
        $choices = $this->parseChoices( $choices );

        foreach( $choices as $choice ){

            $html .= $this->buildChoice( $choice );

        }

        return $html;
    }


    /**
     * Return html for a single choice
     * 
     * @param  array/string $choice
     * @return string HTML
     */
    public function buildChoice( $choice ){

        $html = '';

        //set choice variables:
        $id = 'subfield-'.$this->id.'-'.$choice['id'];
        $value = $choice['key'];
        $label = ( isset( $choice['label'] ) ? $choice['label'] : false );
        $selected = $this->getSelectedType();
        $defaultValues = $this->properties['defaultValue'];

        $html = '<span class="subfield-wrapper '.$value.'">';

            $html .= '<input type="'.$this->type.'" ';

            $html .= 'id="'.$id.'" ';

            $html .= 'class="'.$this->getSubClass().'" ';

            $html .= $this->getNameAttr( $value );

            $html .= 'value="'.$value.'" ';

            $html .= $this->getValidation();

           if( is_array( $defaultValues ) )
                $html .= ( in_array( $value, $defaultValues ) ? ' '.$selected : '' );
            

            $html .= '>';

            $html .= '<label for="'.$id.'">';
                $html .= ( $label ? $label : '' );
            $html .= '</label>';

        $html .= '</span>';

        return $html;

    }



}