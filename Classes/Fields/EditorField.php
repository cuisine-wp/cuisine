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

                            'textarea_name' => $this->name

                        )
            );
        echo '</div>';

        return ob_get_clean();

        return $val;
    }




}