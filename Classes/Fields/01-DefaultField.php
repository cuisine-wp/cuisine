<?php
    
namespace Cuisine\Fields;


class DefaultField{

    /**
     * Id of this field
     * 
     * @var String
     */
    var $id;

    /**
     * Name of this field
     * 
     * @var String
     */
    var $name;

    /**
     * Type of this field
     * 
     * @var String
     */
    var $type;

    /**
     * Properties of this field
     * 
     * @var array
     */
    var $properties;

    /**
     * Array of custom classes
     * 
     * @var array
     */
    var $classes = array();


    /**
     * Define a core Field.
     *
     * @param array $properties The text field properties.
     */
    public function __construct( $name, $props = array() ){

        $this->id = md5( $name );
        $this->name = $name;
        $this->properties = $props;
        $this->fieldType();
        $this->setDefaults();

        //cuisine_dump( $this );
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

        echo $this->getLabel();
        echo $this->build();

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

            $html .= $this->getDefault();

            $html .= $this->getPlaceholder();

            $html .= $this->getValidation();

        $html .= '/>';

        return $html;
    }



    /*=============================================================*/
    /**             GETTERS & SETTERS                              */
    /*=============================================================*/

    /**
     * Set the default values:
     *
     * @return void
     */
    private function setDefaults(){


        if( !isset( $this->properties['label'] ) )
            $this->properties['label'] = false;

        if( !isset( $this->properties['defaultValue'] ) )
            $this->properties['defaultValue'] = false;

        if( !isset( $this->properties['placeholder'] ) )
            $this->properties['placeholder'] = false;

        if( !isset( $this->properties['validation'] ) )
            $this->properties['validation'] = false;

        if( !isset( $this->properties['options'] ) )
            $this->properties['options'] = false;

        if( !isset( $this->properties['class'] ) )
            $this->properties['class'] = array(
                                                'field',
                                                'field-'.$this->name,
                                                'type-'.$this->type
            );

    }


    /**
     * Get Label
     * 
     * @return String
     */
    public function getLabel(){

        if( $this->properties['label'] )
            return '<label for="'.$this->id.'">'.$this->properties['label'].'</label>';

    }


    /**
     * Get the default value html
     * 
     * @return String
     */
    public function getDefault(){

        if( $this->properties['defaultValue' ] )
            return ' value="'.$this->properties['defaultValue'].'"';
 
    }


    /**
     * Get placeholder
     * 
     * @return String
     */
    public function getPlaceholder(){

        if( $this->properties['placeholder'] )
            return ' placeholder="'.$this->properties['placeholder'].'"';

    }


    /**
     * Get the validation data-attribute
     * 
     * @return String
     */
    public function getValidation(){

        if( $this->properties['validation'] )
            return ' data-validate="'.implode( ',', $this->properties['validation'] );

    }


    /**
     * Create the class for the html output
     * 
     * @return String
     */
    public function getClass(){

        $classes = $this->properties['class'];
        $classes = array_merge( $classes, $this->classes );
        $output = implode( ' ', $classes );

        return $output;

    }

    /**
     * Get the class of sub-inputs like radios and checkboxes
     * 
     * @return String;
     */
    public function getSubClass(){

        $classes = array(
                            'subfield',
                            'type-'.$this->type
        );

        $classes = apply_filters( 'cuisine_subfield_classes', $classes );
        $output = implode( ' ', $classes );

        return $output;
    }



    /**
     * Get an active / selected state for this field
     * 
     * @return String
     */
    public function getSelectedType(){

        switch( $this->type ){

            case 'radio':
                return 'checked';
                break;
            
            case 'select':
                return 'selected';
                break;

            case 'checkbox':
                return 'checked';
                break;

            default:
                return 'data-selected="true"';
                break; 
        }

    }


}


   
