<?php
namespace Cuisine\Fields;

/**
 * Field factory.
 * @package Cuisine\Field
 */
class FieldBuilder {


    /**
     * Call the appropriate field class.
     *
     * @param string $class The custom field class name.
     * @param array $fieldProperties The defined field properties. Muse be an associative array.
     * @throws FieldException
     * @return object Cuisine\Field\FieldBuilder
     */
    public function make( $class, $name, $label, array $fieldProperties ){

        try {
            // Return the called class.
            $class =  new $class( $name, $label, $fieldProperties );

        } catch(\Exception $e){

            //@TODO Implement log if class is not found

        }

        return $class;

    }

    /**
     * Return a TextField instance.
     *
     * @param string $name The name attribute of the text input.
     * @param string $label The Labelof the text input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\TextField
     */
    public function text( $name, $label = '', array $properties = array() ){

        return $this->make( 'Cuisine\\Fields\\TextField', $name, $label, $properties );

    }


    /**
     * Return a PasswordField instance.
     *
     * @param string $name The name attribute of the password input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\PasswordField
     */
    public function password( $name, $label = '', array $properties = array() ){


        return $this->make( 'Cuisine\\Fields\\PasswordField', $name, $label, $properties );
    }

    /**
     * Return a NumberField instance.
     *
     * @param string $name The name attribute of the number input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\NumberField
     */
    public function number($name, $label = '', array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\NumberField', $name, $label, $properties );

    }

    /**
     * Return a DateField instance.
     *
     * @param string $name The name attribute of the date input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\DateField
     */
    public function date($name, $label = '', array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\DateField', $name, $label, $properties );

    }

    /**
     * Return a TextareaField instance.
     *
     * @param string $name The name attribute of the textarea.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\TextareaField
     */
    public function textarea($name, $label = '', array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\TextareaField', $name, $label, $properties);

    }

    /**
     * Return a CheckboxField instance.
     *
     * @param string $name The name attribute of the checkbox input.
     * @param string|array $options The checkbox options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\CheckboxField
     */
    public function checkbox($name, $label = '', $options, array $properties = array()){

        $extras = compact( 'options' );

        $properties = array_merge( $extras, $properties );

        return $this->make('Cuisine\\Fields\\CheckboxField', $name, $label, $properties );

    }

    /**
     * Return a CheckboxesField instance.
     *
     * @deprecated
     * @param string $name The name attribute.
     * @param array $options The checkboxes options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\CheckboxesField
     */
    public function checkboxes($name, $label = '', array $options, array $properties = array()){

        $extras = compact( 'options');

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\CheckboxesField', $name, $label, $properties );
    }

    /**
     * Return a RadioField instance.
     *
     * @param string $name The name attribute.
     * @param array $options The radio options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\RadioField
     */
    public function radio($name, $label = '', array $options, array $properties = array()){

        $extras = compact( 'options' );

        $properties = array_merge($extras, $properties);

        return $this->make( 'Cuisine\\Fields\\RadioField', $name, $label, $properties );
    }

    /**
     * Define a SelectField instance.
     *
     * @param string $name The name attribute of the select custom field.
     * @param array $options The select options tag.
     * @param bool $multiple
     * @param array $extras
     * @return \Cuisine\Fields\SelectField
     */
    public function select( $name, $label = '', array $options, array $properties = array() ){

        $extras = compact( 'options' );
        
        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\SelectField', $name, $label, $properties );
    }

    /**
     * Return a MediaField instance.
     *
     * @param string $name The name attribute of the media input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\MediaField
     */
    public function media($name, $label = '', array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\MediaField', $name, $label, $properties );
    }


    /**
     * Return a ImageField instance.
     *
     * @param string $name The name attribute of the image input.
     * @param string $label The Labelof the image input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\ImageField
     */
    public function image( $name, $label = '', array $properties = array() ){

        return $this->make( 'Cuisine\\Fields\\ImageField', $name, $label, $properties );

    }

    /**
     * Define an RepeaterField instance.
     *
     * @param string $name The name attribute of the infinite inner inputs.
     * @param array $fields The fields to repeat.
     * @param array $extras
     * @return \Cuisine\Fields\InfiniteField
     */
    public function repeater($name, $label = '', array $fields, array $properties = array()){

        $extras = compact( 'fields' );

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\InfiniteField', $name, $label, $properties );
    }


    /**
     * Define an FlexField instance.
     *
     * @param string $name The name attribute of the infinite inner inputs.
     * @param array $fields The fields to repeat.
     * @param array $extras
     * @return \Cuisine\Fields\FlexField
     */
    public function flex($name, $label = '', array $fields, array $properties = array()){

        $extras = compact( 'fields' );

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\FlexField', $name, $label, $properties );
    }




    /**
     * Define an EditorField instance.
     * @link http://codex.wordpress.org/Function_Reference/wp_editor
     *
     * @param string $name The name attribute if the editor field.
     * @param array $settings The 'wp_editor' settings.
     * @param array $extras
     * @return \Cuisine\Fields\EditorField
     */
    public function editor($name, $label = '', array $properties = array(), array $settings = array() ){

        // $name may only contain lower-case characters.
        $name = strtolower($name);

        $extras = compact( 'settings' );
        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\EditorField', $name, $label, $properties );
    }


    /**
     * Return a HiddenField instance.
     *
     * @param string $name The name attribute of the text input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Fields\TextField
     */
    public function hidden( $name, array $properties = array() ){

        return $this->make( 'Cuisine\\Fields\\HiddenField', $name, '', $properties );

    }




    /**
     * If a field doesn't exist, try to locate it.
     *
     * @param string $name Name of the method
     * @param  array $attr
     * @return self::$name(), if it exists.
     */
    public function __call( $name, $attr ){

        $types = $this->getAvailableTypes();
        $names = array_keys( $types );

        //if method can be found:
        if( in_array( $name, $names ) ){

            $method = $types[ $name ];
            $props = ( isset( $attr[1] ) ? $attr[1] : array() );
            return $this->make( $method['class'], $attr[0], $props );
        }

        return false;
    }


    /*=============================================================*/
    /**             GETTERS & SETTERS                              */
    /*=============================================================*/


    /**
     * Returns a filterable array of field types
     *
     * @filter cuisine_field_types
     * @return array
     */
    public function getAvailableTypes(){

        $arr = array(

            'text'       => array(

                'name'      => __( 'Tekst', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\TextField'
            ),

            'password'      => array(
                'name'      => __( 'Wachtwoord', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\PasswordField'
            ),

            'number'        => array(
                'name'      => __( 'Nummer', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\NumberField',            
            ),

            'date'          => array(

                'name'      => __( 'Datum', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\DateField',
            ),

            'textarea'      => array(

                'name'      => __( 'Tekstvlak', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\TextareaField'
            ),

            'checkbox'      => array( 

                'name'      => __( 'Checkbox', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\CheckboxField'
            ),

            'checkboxes'    => array( 

                'name'      => __( 'Checkboxes', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\CheckboxesField'
            ),
            'radio'         => array( 

                'name'      => __( 'Radio buttons', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\RadioField'
            ),
            'select'        => array( 

                'name'      => __( 'Select', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\SelectField'
            ),
            'media'         => array( 

                'name'      => __( 'Media', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\MediaField'
            ),
            'repeater'      => array( 

                'name'      => __( 'Repeater', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\RepeaterField'
            ),
            'flex'          => array( 

                'name'      => __( 'Flex', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\FlexField'
            ),
            'editor'        => array( 

                'name'      => __( 'Wysiwyg Editor', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\EditorField'
            ),
            'hidden'      => array( 

                'name'      => __( 'Verborgen', 'cuisine' ),
                'class'     => 'Cuisine\\Fields\\HiddenField'
            ),
        );


        $arr = apply_filters( 'cuisine_field_types', $arr );
        return $arr;
    }



} 