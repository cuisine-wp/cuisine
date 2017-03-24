<?php
namespace Cuisine\Builders;

use Cuisine\Utilities\Session;
use Cuisine\Wrappers\User;

class UsermetaBuilder {

	
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
	 * The metabox view, in raw html
	 *
	 * @var html
	 */
	protected $view;


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
	 * Build a User-metabox instance.
	 *
	 * @param \Cuisine\Validation\Validation $validator
	 * @param \Cuisine\User\User $user
	 */
	function __construct(){

		//$this->user = $user;
		$this->data = array();

		add_action( 'personal_options_update', array( &$this, 'save' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'save' ) );
	}


	/**
	 * Set a new metabox.
	 *
	 * @param string $title The metabox title.
	 * @param string $postType The metabox parent slug name.
	 * @param array $options Metabox extra options.
	 * @param \Cuisine\View\MetaboxView
	 * @return object
	 */
	public function make( $title, array $options = array() ){

	  	$this->data['title'] = $title;
	    $this->data['options'] = $this->parseOptions($options);

	    return $this;
	}

	/**
	 * Build the set metabox.
	 *
	 * @param array $fields A list of fields to display.
	 * @return \Cuisine\Metabox\UsermetaBuilder
	 */
	public function set( $contents = array() ){

		//if it's an array, contents contains fields
	    if( is_array( $contents ) ){

	    	// Check if sections are defined.
//	    	$this->sections = $this->getSections( $contents );
	    	$this->sections = array();

		    $this->data['fields'] = $contents;
		    $this->data['render'] = 'self';
		
		//else it contains a view:
		}else{
		
			$this->data['fields'] = array();
			$this->data['render'] = $contents;

		}

		add_action( 'show_user_profile', array( &$this, 'display' ) );
		add_action( 'edit_user_profile', array( &$this, 'display' ) );

	    return $this;
	}


	/**
	 * Populate fields with default user meta values
	 * 
	 * @param array $fields
	 * @return array $fields
	 */
	public function setFields( $fields ){

		global $current_user;
		$user_id = ( isset( $_GET['user_id'] ) ? $_GET['user_id'] : $current_user->ID );

		$i = 0;
		foreach( $fields as $field ){

			$meta = get_user_meta( $user_id, $field->name, true );
			if( $meta )
				$fields[ $i ]->properties['defaultValue'] = $meta;

			$i++;
		}

		return $fields;
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
	 * The wrapper display method.
	 *
	 * @return void
	 */
	public function display(){

	    if( $this->check && !User::can( $this->capability ) ) return;

	    if( $this->data['render'] == 'self' ){
	    	
	    	$this->render();

	    }else{

	    	//call the supplied function:
	    	call_user_func_array( $this->data['render'] , $this->data['options'] );
	    }

	}


	/**
	 * Call by "display", build the HTML code.
	 *
	 * @param array $datas The metabox $args and associated fields.
	 * @throws MetaboxException
	 * @return void
	 */
	public function render() {
	   
	    // Add nonce fields
	    wp_nonce_field( Session::nonceAction, Session::nonceName );

	    echo '<div class="user-meta-box">';

	    if( $this->data['title'] != '' )
	   		echo '<h3>'.$this->data['title'].'</h3>';

	   	$fields = $this->setFields( $this->data['fields'] );
	    foreach( $fields as $field ){

	    	$field->render();

	    }


	    //render the javascript-templates seperate, to prevent doubles
	    $rendered = array();

	    foreach( $fields as $field ){



	    	if( method_exists( $field, 'renderTemplate' ) && !in_array( $field->name, $rendered ) ){

	    		echo $field->renderTemplate();
	    		$rendered[] = $field->name;

	    	}
	    }

	    echo '</div>';
	}


	/**
	 * The wrapper install method. Save container values.
	 *
	 * @param int $postId The post ID value.
	 * @return void
	 */
	public function save( $userId ){

	    $nonceName = (isset($_POST[Session::nonceName])) ? $_POST[Session::nonceName] : Session::nonceName;
	    if (!wp_verify_nonce($nonceName, Session::nonceAction)) return;

	    // Check user capability.
		if ( !User::can( 'edit_user', $userId ) ) return;


	    $fields = array();
	    
	    // Loop through the registered fields.
	    // With sections.
	    if ( !empty($this->sections ) ) {
	        
	        foreach ($this->data['fields'] as $fs){

	            $fields = $fs;
	        
	        }

	    } else {

	        $fields = $this->data['fields'];
	    
	    }

	    $this->register( $userId, apply_filters( 'cuisine_before_user_field_save', $fields, $userId, $this->data ) );

	}

	/**
	 * Register the metabox and its fields into the DB.
	 *
	 * @param int $userId
	 * @param array $fields
	 * @return void
	 */
	protected function register( $userId, $fields ) {

	    foreach( $fields as $field ){

	    	$key = $field->name;

	    	//change the value for editors, as the $_POST
	    	//variable for that field is different
	    	if( $field->type == 'editor' )
	    		$key = $field->id;


			$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
			update_user_meta( $userId, $field->name, $value );
	    
	    }
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