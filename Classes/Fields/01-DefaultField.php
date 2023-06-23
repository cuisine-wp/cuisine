<?php
    
namespace Cuisine\Fields;

use Cuisine\Wrappers\User;

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
     * Array of user roles needed for this field
     * 
     * @var array
     */
    var $userRoles = array();



    /**
     * Define a core Field.
     *
     * @param array $properties The text field properties.
     */
    public function __construct( $name, $label = '', $props = array() ){

        $this->id = $this->createId( $name, $label, $props );

        $this->name = $name;
        $this->label = $label;
        $this->properties = $props;
        $this->fieldType();
        $this->setDefaults();
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

        $class = $this->getWrapperClass();

        do_action( 'before_field_'.$this->name, $this );

        echo '<div class="'.$class.'">';

            echo $this->getLabel();
            echo $this->build();

        echo '</div>';

        do_action( 'after_field_'.$this->name, $this );
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
            $this->properties['class'] = array();

        if( !is_array( $this->properties['class'] ) )
            $this->properties['class'] = array( $this->properties['class'] );

        if( !isset( $this->properties['classes'] ) )
            $this->properties['classes'] = array();

        if( !isset( $this->properties['wrapper-class'] ) )
            $this->properties['wrapper-class'] = array();

        if( !isset( $this->properties['userRoles' ] ) )
            $this->properties['userRoles'] = apply_filters( 'cuisine_default_field_user_roles', [ 
                'editor',
                'administrator'
        ]);


        //base classes
        $this->classes = array(
            'field',
            'input-field',
            'field-'.$this->name,
            'type-'.$this->type
        );


        //set user capabilities:
        $this->userRoles = $this->properties['userRoles'];

    }


    /**
     * Allow it that this field's name will be changed retroactively
     * 
     * @param string $name
     * @return void
     */
    public function setName( $name ){

        $this->name = $name;
        $this->id = $this->createId( $name, $this->label, $this->properties );

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

        if( $value && !$val )
            $val = $value;

        if( $this->properties['defaultValue'] && !$val )
            $val = $this->getDefault();

        if( $this->getProperty('stripSlashes') == true )
            $val = stripcslashes( $val );


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
        
        if( !is_array( $this->properties['class'] ) )
            $this->properties['class'] = [ $this->properties['class'] ];

        if( !is_array( $this->properties['classes'] ) )
            $this->properties['classes'] = [ $this->properties['classes'] ];

        $classes = array_merge( $this->properties['class'], $this->classes );
        $classes = array_merge( $this->properties['classes'], $classes );
        $output = implode( ' ', $classes );

        return $output;

    }


    /**
     * Returns the wrapper class
     * 
     * @return String
     */
    public function getWrapperClass()
    {
        $classes = [];
        $classes[] = 'field-wrapper';
        $classes[] = $this->type;
        $rightRole = false;

        //check if thie right user-roles are present:
        if( !empty( $this->userRoles ) && is_admin() ){

            foreach( $this->userRoles as $role ){
                if( User::hasRole( $role ) ){
                    $rightRole = true;
                    break;
                }
            }
            
            if( !$rightRole )
                $classes[] = 'user-no-valid-role';
        }


        if( $this->properties['label'] )
            $classes[] = ' label-'.sanitize_title( $this->properties['label'] );

        if( $this->properties['wrapper-class'] && is_array( $this->properties['wrapper-class'] ) )
            $classes = array_merge( $classes, $this->properties['wrapper-class'] );

        $output = implode( ' ', $classes );
        return $output;
    }


    /**
     * Get a field-property
     * 
     * @param  string $name
     * @return mixed (returns false when the property doesn't exist)
     */
    public function getProperty( $name, $default = false ){

        if( isset( $this->properties[ $name ] ) )
            return $this->properties[ $name ];

        return $default;
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


    /**
     * Create a unique ID for this field:
     * 
     * @param  string $name  
     * @param  string $label 
     * @param  Array $array 
     * @return
     */
    private function createId( $name, $label, $array ){

        unset( $array['value'] );
        unset( $array['defaultValue'] );
        unset( $array['placeholder'] );


        $string = $name . $label . serialize( $array );
        return md5( $string );

    }


}


   
