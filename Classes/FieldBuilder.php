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
    public function make( $class, $name, array $fieldProperties ){

        try {
            // Return the called class.
            $class =  new $class( $name, $fieldProperties );

        } catch(\Exception $e){

            //@TODO Implement log if class is not found

        }

        return $class;

    }

    /**
     * Return a TextField instance.
     *
     * @param string $name The name attribute of the text input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\TextField
     */
    public function text( $name, array $properties = array() ){

        return $this->make( 'Cuisine\\Fields\\TextField', $name, $properties );

    }


    /**
     * Return a PasswordField instance.
     *
     * @param string $name The name attribute of the password input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\PasswordField
     */
    public function password( $name, array $properties = array() ){


        return $this->make( 'Cuisine\\Fields\\PasswordField', $name, $properties );
    }

    /**
     * Return a NumberField instance.
     *
     * @param string $name The name attribute of the number input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\NumberField
     */
    public function number($name, array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\NumberField', $name, $properties );

    }

    /**
     * Return a DateField instance.
     *
     * @param string $name The name attribute of the date input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\DateField
     */
    public function date($name, array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\DateField', $name, $properties );

    }

    /**
     * Return a TextareaField instance.
     *
     * @param string $name The name attribute of the textarea.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\TextareaField
     */
    public function textarea($name, array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\TextareaField', $name, $properties);

    }

    /**
     * Return a CheckboxField instance.
     *
     * @param string $name The name attribute of the checkbox input.
     * @param string|array $options The checkbox options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\CheckboxField
     */
    public function checkbox($name, $options, array $properties = array()){

        $extras = compact( 'options' );

        $properties = array_merge( $extras, $properties );

        return $this->make('Cuisine\\Fields\\CheckboxField', $name, $properties );

    }

    /**
     * Return a CheckboxesField instance.
     *
     * @deprecated
     * @param string $name The name attribute.
     * @param array $options The checkboxes options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\CheckboxesField
     */
    public function checkboxes($name, array $options, array $properties = array()){

        $extras = compact( 'options');

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\CheckboxesField', $name, $properties );
    }

    /**
     * Return a RadioField instance.
     *
     * @param string $name The name attribute.
     * @param array $options The radio options.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\RadioField
     */
    public function radio($name, array $options, array $properties = array()){

        $extras = compact( 'options');

        $properties = array_merge($extras, $properties);

        return $this->make( 'Cuisine\\Fields\\RadioField', $name, $properties );
    }

    /**
     * Define a SelectField instance.
     *
     * @param string $name The name attribute of the select custom field.
     * @param array $options The select options tag.
     * @param bool $multiple
     * @param array $extras
     * @return \Cuisine\Field\Fields\SelectField
     */
    public function select( $name, array $options, $multiple = false, array $properties = array() ){

        $extras = compact('name', 'options');

        // Check the multiple attribute.
        if( $multiple == true ){

            $properties['multiple'] = 'multiple';
        }

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\SelectField', $name, $properties );
    }

    /**
     * Return a MediaField instance.
     *
     * @param string $name The name attribute of the hidden input.
     * @param array $extras Extra field properties.
     * @return \Cuisine\Field\Fields\MediaField
     */
    public function media($name, array $properties = array()){

        return $this->make( 'Cuisine\\Fields\\MediaField', $name, $properties );
    }

    /**
     * Define an RepeaterField instance.
     *
     * @param string $name The name attribute of the infinite inner inputs.
     * @param array $fields The fields to repeat.
     * @param array $extras
     * @return \Cuisine\Field\Fields\InfiniteField
     */
    public function repeater($name, array $fields, array $properties = array()){

        $extras = compact( 'fields' );

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\InfiniteField', $name, $properties );
    }


    /**
     * Define an FlexField instance.
     *
     * @param string $name The name attribute of the infinite inner inputs.
     * @param array $fields The fields to repeat.
     * @param array $extras
     * @return \Cuisine\Field\Fields\FlexField
     */
    public function flex($name, array $fields, array $properties = array()){

        $extras = compact( 'fields' );

        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\FlexField', $name, $properties );
    }




    /**
     * Define an EditorField instance.
     * @link http://codex.wordpress.org/Function_Reference/wp_editor
     *
     * @param string $name The name attribute if the editor field.
     * @param array $settings The 'wp_editor' settings.
     * @param array $extras
     * @return \Cuisine\Field\Fields\EditorField
     */
    public function editor($name, array $settings = array(), array $properties = array()){

        // $name may only contain lower-case characters.
        $name = strtolower($name);

        $extras = compact( 'settings' );
        $properties = array_merge( $extras, $properties );

        return $this->make( 'Cuisine\\Fields\\EditorField', $name, $properties );
    }

    /**
     * Define a CollectionField instance.
     *
     * @param string $name The name attribute.
     * @param array $extras
     * @return \Cuisine\Field\Fields\CollectionField
     */
    public function collection($name, array $properties = array()){
        
        return $this->make( 'Cuisine\\Fields\\CollectionField', $name, $properties );

    }

} 