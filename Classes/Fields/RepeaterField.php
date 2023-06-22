<?php
namespace Cuisine\Fields;

use Cuisine\Utilities\Sort;

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

        $class = 'repeater-field';
        $datas = $this->buildDatas();

        if( $this->getProperty( 'view' ) )
            $class .= ' '.$this->getProperty( 'view' );

        echo '<div class="'.$class.'" '.$datas.'>';

            echo $this->getLabel();
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

            $values = array_values( $values );
            $values = Sort::byField( $values, 'position', 'ASC' );

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


                //change field-name for rendering:
                $field->setName( $name );

                if( $field->type !== 'editor' || $doingAjax == false ){
                    $field->render();

                }else{

                    $field->renderForAjax();

                }

                //change the name right back:
                $field->setName( $oldName );

            }

            $v = '';
            if( isset( $value['position'] ) )
                $v = 'value="'.$value['position'].'"';

            //add position field:
            echo '<div class="field-wrapper" style="display:none">';    
                echo '<input type="hidden" '.$v.' class="multi" name="'.$prefix.'[position]" id="position"/>';
            echo '</div>';
            
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

        $highestId = 0;
        $value = $this->getValues();
        if( $value !== false ){
            $highestId = count( $value );
        }
        
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

        echo '<div class="sort-pin"><span class="dashicons dashicons-sort"></span></div>';

    }



    /**
     * Generate a unique template-id
     *
     * @return string
     */
    private function getTemplateName(){

        if( !isset( $_GET['post'] ) && isset( $_GET['page' ] ) )
            return $_GET['page'].'-'.$this->name;


        global $post; 
       return $post->ID.'-'.$this->name;

    }

    /**
     * Returns an array with all repeated layouts, including post values
     *
     * @return array
     */
    public function getFieldValues(){

        $fieldLayouts = array();
        $this->fields = $this->properties['fields'];

        //check if this is a post-value:
        if( isset( $_POST[ $this->name ] ) ){

            $fieldLayouts = $_POST[ $this->name ];

            //store the editor-field-name as we'll be changing it later, to get the right id
            $_fieldName = '';

            //for each repeater layout:
            foreach( $fieldLayouts as $key => $entry ){

                $fieldLayouts[ $key ] = array();
                $entryKeys = array_keys( $entry );

                //loop through the fields, find the right values
                foreach( $this->fields as $field ){

                    //set the default value:
                    $value = $field->getDefault();

                    //editors use there IDs to POST, so they are the exception:
                    if( $field->type !== 'editor' && isset( $entry[ $field->name ] ) ){

                        $value = $entry[$field->name];

                    }else if( $field->type == 'editor' ){

                        //the editor name needs to be set correctly, to get the right ID:
                        $name = $this->name.'['.$key.']['.$field->name.']';
                        $id = $field->createId( $name, $field->label, $field->properties );

                        if( isset( $_POST[ $id ] ) )
                            $value = $_POST[ $id ];

                    }

                    //set the value to the right key:
                    $fieldLayouts[ $key ][ $field->name ] = $value;

                }
           
                $fieldLayouts[ $key ]['position'] = $entry['position'];
            }
        }

        return $fieldLayouts;
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

        if( is_array( $val ) )
            $val = array_values( $val );


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
