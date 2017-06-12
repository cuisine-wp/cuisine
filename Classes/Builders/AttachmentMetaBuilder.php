<?php

namespace Cuisine\Builders;

use Cuisine\Utilities\Session;
use Cuisine\Wrappers\User;

class AttachmentMetaBuilder {

	
	/**
	 * Metabox instance data.
	 *
	 * @var Array
	 */
	protected $data;


	/**
	 * The current user instance.
	 *
	 * @var \Cuisine\Utilities\User
	 */
	protected $user;


	/**
	 * Whether or not check for user capability.
	 *
	 * @var bool
	 */
	protected $check = false;

	/**
	 * The capability to check.
	 *
	 * @var string
	 */
	protected $capability;



	/**
	 * Build a metabox instance.
	 *
	 * @param \Cuisine\Validation\Validation $validator
	 * @param \Cuisine\User\User $user
	 */
	function __construct(){

		//$this->user = $user;
		$this->data = array();
	   	$this->sections = array();

	}


	/**
	 * Set a new metabox.
	 *
	 * @return object
	 */
	public function make(){
	    add_filter( 'attachment_fields_to_edit', array( $this, 'setFields' ), 11, 2 );
	   	add_filter( 'attachment_fields_to_save', array( $this, 'save' ), 11, 2 );
	    return $this;

	}

	/**
	 * Build the set metabox.
	 *
	 * @param array $fields A list of fields to display.
	 * @return \Cuisine\Metabox\MetaboxBuilder
	 */
	public function set( $contents = array() ){

    	// Check if sections are defined.
		// $this->sections = $this->getSections( $contents );
	    $this->data['fields'] = $contents;
	    //cuisine_dump( $this->data['fields'] );
	    return $this;
	}


	/**
	 * Set attachment fields
	 * 
	 */
	public function setFields( $formFields, $post = null)
	{
		$pid = null;
		if( !is_null( $post ) )
			$pid = $post->ID;

		$fields = $this->populateFields( $pid );

		if( !empty( $fields ) ){
			foreach( $fields as $field ){

				ob_start();
				
					$field->render();
				
				$fieldOutput = ob_get_clean();

                // We add our field into the $form_fields array
                $formFields[$field->name] = [
                	'input' => 'html',
                	'label' => $field->getLabel(),
                	'html' => $fieldOutput
                ];

			}
		}

		return $formFields;
	}


	/**
	 * Restrict access to a specific user capability.
	 *
	 * @param string $capability
	 * @return void
	 */
	public function can($capability){
	    $this->capability = $capability;
	    $this->check = true;
	
	}


	/**
	 * Populate fields with default user meta values
	 * 
	 * @param array $fields
	 * @return array $fields
	 */
	public function populateFields( $postId ){

		$fields = $this->data['fields'];

		$i = 0;
		foreach( $fields as $field ){

			$meta = get_post_meta( $postId, $field->name, true );
			if( $meta ){
				$fields[ $i ]->properties['label'] = false;
				$fields[ $i ]->properties['defaultValue'] = $meta;
			}

			$i++;
		}

		return $fields;
	}

	/**
	 * The wrapper install method. Save container values.
	 *
	 * @param int $postId The post ID value.
	 * @return void
	 */
	public function save( $post, $attachment ){

	  	foreach( $this->data['fields'] as $field ){

	  		$key = $field->name;

	    	//change the value for editors, as the $_POST
	    	//variable for that field is different
	    	if( $field->type == 'editor' )
	    		$key = $field->id;



			$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
			update_post_meta( $post['ID'], $field->name, $value );

		}

	}

	/**
	 * Register validation rules for the custom fields.
	 *
	 * @param array $rules A list of field names and their associated validation rule.
	 * @return \Cuisine\Metabox\MetaboxBuilder
	 */
	public function validate(array $rules = array()) {

	    $this->data['rules'] = $rules;

	    return $this;
	}


	/**
	 * Check metabox options: context, priority.
	 *
	 * @param array $options The metabox options.
	 * @return array
	 */
	protected function parseOptions(array $options) {

	    return wp_parse_args($options, array(
	        'context'   => 'normal',
	        'priority'  => 'default'
	    ));
	
	}


	/**
	 * Set the metabox view sections.
	 *
	 * @param array $fields
	 * @return array
	 */
	protected function getSections(array $fields) {

	    $sections = array();

	    foreach ($fields as $section => $subFields) {
	        
	        if ( !is_numeric( $section ) ) {
	        
	            array_push($sections, $section);
	        
	        }
	    }

	    return $sections;
	}

	/**
	 * Set the default 'value' property for all fields.
	 *
	 * @param \WP_Post $post
	 * @param array $fields
	 * @return void
	 */
	protected function setDefaultValue( \WP_Post $post, array $fields ) {
	    
	    foreach ( $fields as $field ){

	        // Check if saved value
	        $value = get_post_meta($post->ID, $field['name'], true);

	        // If none of the above condition is matched
	        // simply assign the post meta default or saved value.
	        //$field['value'] = $this->parseValue($field, $value);
	    	$field['value'] = '';
	    }
	}









}

