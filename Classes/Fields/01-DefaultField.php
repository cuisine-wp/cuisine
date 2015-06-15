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
     * Label of this field
     * 
     * @var String
     */
    var $label;

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
    public function __construct( $name, $label = '', $props = array() ){

        $this->id = md5( $name );
        $this->name = $name;
        $this->label = $label;
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

        $class = 'field-wrapper';

        $class .= ' '.$this->type;

        if( $this->properties['label'] )
            $class .= ' label-'.$this->properties['label'];

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

        $html = '<input type="'.$this->type.'" ';

            $html .= 'id="'.$this->id.'" ';

            $html .= 'class="'.$this->getClass().'" ';

            $html .= 'name="'.$this->name.'" ';

            $html .= $this->getValueAttr();

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
            $this->properties['label'] = 'top';

        //default value
        if( !isset( $this->properties['defaultValue'] ) )
            $this->properties['defaultValue'] = false;

        //actual value
        if( !isset( $this->properties['value'] ) )
            $this->properties['value'] = false;

        if( !isset( $this->properties['placeholder'] ) )
            $this->properties['placeholder'] = false;

        if( !isset( $this->properties['required'] ) )
            $this->properties['required'] = false;

        if( !isset( $this->properties['validation'] ) )
            $this->properties['validation'] = array();

        if( !isset( $this->properties['options'] ) )
            $this->properties['options'] = false;

        if( !isset( $this->properties['class'] ) )
            $this->properties['class'] = array(
                                                'field',
                                                'input-field',
                                                'field-'.$this->name,
                                                'type-'.$this->type
            );

    }


    /**
     * Allow it that this field's name will be changed retroactively
     * 
     * @param string $name
     * @return void
     */
    public function setName( $name ){

        $this->name = $name;

    }


    /**
     * Get Label
     * 
     * @return String
     */
    public function getLabel(){

        if( $this->label !== '' && $this->properties['label'] )
            return '<label for="'.$this->id.'">'.$this->label.'</label>';

    }

    /**
     * Returns the value attribute
     * 
     * @return String
     */
    public function getValueAttr(){

        $val = $this->getValue();
        if( $val )
            return ' value="'.$val.'"';

    }


    /**
     * Get the value of this field:
     * 
     * @return String
     */
    public function getValue(){

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
     * Add a validation requirement:
     * 
     * @param string $name
     * @return void
     */
    public function addValidation( $name ){

        if( !isset( $this->properties[ 'validation' ] ) || !is_array( $this->properties[ 'validation' ]  ) )
            $this->properties['validation'] = array();

        if( !in_array( $name, $this->properties[ 'validation' ] ) )
            $this->properties['validation'][] = $name;

    }


    /**
     * Get the validation data-attribute
     * 
     * @return String
     */
    public function getValidation(){

        if( $this->properties['required'] )
            $this->addValidation( 'required' );

        if( !empty( $this->properties['validation'] ) )
            return ' data-validate="'.implode( ',', $this->properties['validation'] ).'"';

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


   
