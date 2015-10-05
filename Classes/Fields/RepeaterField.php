<?php
namespace Cuisine\Fields;


class RepeaterField extends DefaultField{

    /**
     * Array that holds all fields
     * 
     * @var array
     */
    var $fields = array();


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'repeater';
    }

    /**
     * Handle the field HTML code for metabox output.
     *
     * @return string
     */
    public function render(){

        $this->fields = $this->properties['fields'];

        $datas = $this->buildDatas();

        echo '<div class="repeater-field" '.$datas.'>';

            $this->build();

        echo '</div>';

        echo $this->renderPrivateTemplate();
      
    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $values = $this->getValues();

        $i = 0;
        if( !empty( $values ) ){

            foreach( $values as $id => $value ){

                $this->makeItem( $value, $id, false );
                $i++;
            }

        }else{

            $prefix = $this->name.'[0]';
            $this->makeItem( $this->fields, '0' );

        }       
    }


    /**
     * Get a single repeatable
     * 
     * @return String
     */
    public function makeItem( $value, $id, $doingAjax = true ){

        $prefix = $this->name.'['.$id.']';

        echo '<div class="repeatable">';
        
            foreach( $this->fields as $field ){
            
                $oldName = $field->name;
                $name = $prefix.'['.$field->name.']';

                    
                $val = ( isset( $value[$field->name] ) ? $value[$field->name] : '' );

                $field->properties['defaultValue'] = $val;

                if( !in_array( 'multi', $field->classes ) )
                    $field->classes[] = 'multi';


                $field->setName( $name );
               
               if( $field->type !== 'editor' || $doingAjax == false ){    
                    $field->render();

               }else{

                    $field->renderForAjax();

               }

                //change the name right back:
                $field->setName( $oldName );
        
            }
        
            $this->buildControls();
        
            echo '<div class="clearfix"></div>';
        echo '</div>';

    }

    /**
     * Return the template, for Javascript
     * 
     * @return String
     */
    public function renderPrivateTemplate(){

        //make a clonable item, for javascript:
        echo '<script type="text/template" id="'.$this->getTemplateName().'">';

            $this->makeItem( $this->properties['fields'], '<%= highest_id %>' );

        echo '</script>';

    }



    /**
     * Build the data attributes
     * 
     * @return void
     */
    private function buildDatas(){

        $highestId = count( $this->getValues() );
        if( $highestId == 0 ) $highestId = 1;

        $datas = 'data-highest-id="'.$highestId.'" ';
        $datas .= 'data-template="'.$this->getTemplateName().'" ';

        return $datas;
    }

    /**
     * Create + and - icons
     * 
     * @return string ( html, echoed )
     */
    private function buildControls(){

        echo '<div class="repeat-controls">';

            echo '<div class="plus btn"><span class="dashicons dashicons-plus"></span></div>';
            echo '<div class="min btn"><span class="dashicons dashicons-minus"></span></div>';

        echo '</div>';
    }



    /**
     * Generate a unique template-id
     * 
     * @return string
     */
    private function getTemplateName(){

        global $post;
        return $post->ID.'-'.$this->name;

    }


    /**
     * Get sanitized values for this field
     * 
     * @return array
     */
    private function getValues(){

        global $post;
        $value = $val = false;

        if( isset( $post ) )
            $value = get_post_meta( $post->ID, $this->name, true );


        if( $value && !$val )
            $val = $value;

        if( $this->properties['defaultValue'] && !$val )
            $val = $this->getDefault();

        return $val;


    }


    /**
     * Get the default value html
     * 
     * @return String
     */
    public function getDefault(){

        if( $this->properties['defaultValue' ] )
            return $this->properties['defaultValue'];
    
        return false;

    }



}