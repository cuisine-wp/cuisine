<?php
namespace Cuisine\Fields;


class DateField extends DefaultField{

    /**
     * Custom classes
     * 
     * @var array
     */
    var $classes = array( 'datepicker' );


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'text';
    }


    /*=============================================================*/
    /**             RENDERING                                      */
    /*=============================================================*/

    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){

        $class = 'field-wrapper';

        $class .= ' '.$this->type;

        $class .= ' date-wrapper';

        if( $this->properties['label'] )
            $class .= ' label-'.$this->properties['label'];

        echo '<div class="'.$class.'">';

            echo $this->getLabel();
            echo $this->build();

        echo '</div>';
    }
    
    
     /**
     * Create the class for the html output
     * 
     * @return String
     */
    public function getClass(){
        
        $classes = array_merge( $this->properties['class'], $this->classes );
        $classes = array_merge( $this->properties['classes'], $classes );
        $classes[] = 'datepicker';
        $output = implode( ' ', $classes );

        return $output;

    }



}