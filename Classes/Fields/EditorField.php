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
     * Handle the field HTML output for ajax:
     *
     * @return string
     */
    public function renderForAjax(){

        $class = 'field-wrapper';
        $class .= ' '.$this->type;

        if( $this->properties['label'] )
            $class .= ' label-'.$this->properties['label'];

        echo '<div class="'.$class.'">';

            echo $this->getLabel();

            echo '<div class="warning">';

                $l = ( $this->label !== '' ? '"'.$this->label.'"' : __( 'dit veld', 'cuisine' ) );
                echo '<p>'.sprintf( __( 'Klik aub op de "update"-knop om %s weer te geven', 'cuisine' ), $l ).'</p>';

            echo '</div>';
            
        echo '</div>';
    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){
        
        $val = $this->getValue();
        if( !$val ) $val = '';
        
        ob_start();
            
        echo '<div class="editor-wrapper" data-id="'.$this->id.'" data-name="'.$this->name.'">';
            
            wp_editor( 
                        $val,
                        $this->id,
                        array(

                            'textarea_name' => $this->id,
                            'quicktags' => false
                        )
            );
        
        echo '</div>';

        return ob_get_clean();

        return $val;
    }


    /**
     * Create a unique ID for this field:
     * 
     * @param  string $name  
     * @param  string $label 
     * @param  Array $array 
     * @return
     */
    public function createId( $name, $label, $array ){

        unset( $array['value'] );
        unset( $array['defaultValue'] );
        unset( $array['placeholder'] );


        $string = $name . $label . serialize( $array );
        return md5( $string );

    }

}