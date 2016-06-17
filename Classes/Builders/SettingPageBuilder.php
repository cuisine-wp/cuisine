<?php
namespace Cuisine\Builders;

use Cuisine\Utilities\Session;
use Cuisine\Utilities\User;

class SettingsPageBuilder {

	
	/**
	 * SettingPAge instance data.
	 *
	 * @var Array
	 */
	private $data;


	/**
	 * The current user instance.
	 *
	 * @var \Cuisine\Utilities\User
	 */
	private $user;


	/**
	 * The settings page view, in raw html
	 *
	 * @var html
	 */
	private $view;


	/**
	 * Whether or not check for user capability.
	 *
	 * @var bool
	 */
	private $check = false;

	/**
	 * The capability to check.
	 *
	 * @var string
	 */
	private $capability;



	/**
	 * Build a settings page instance.
	 *
	 * @param \Cuisine\Validation\Validation $validator
	 * @param \Cuisine\User\User $user
	 */
	function __construct(){

		$this->data = array();

	}


	/**
	 * Set a new settings page.
	 *
	 * @param string $title The settings page title.
	 * @param string $slug The settings page slug name.
	 * @param array $options SettingPage extra options.
	 * @param \Cuisine\View\SettingPageView
	 * @return object
	 */
	public function make( $title, $slug, array $options = array(), $view = null ){

	  	$this->data['title'] = $title;
	  	$this->data['form-title'] = $this->getOptionName();
	    $this->data['slug'] = $slug;
	    $this->data['options'] = $this->parseOptions($options);
	    $this->capability = $this->data['options']['capability'];

	    if ( !is_null( $view ) )
	        $this->view = $view;
	    

	    return $this;
	}


	/**
	 * Build the set settings page.
	 *
	 * @param array $fields A list of fields to display.
	 * @return \Cuisine\Builders\SettingPageBuilder
	 */
	public function set( $contents = array() ){


		//if it's an array, contents contains fields
	    if( is_array( $contents ) ){

		    $this->data['fields'] = $contents;
		    $this->data['render'] = array( &$this, 'render' );
		
		//else it contains a view:
		}else{
		
			$this->data['fields'] = array();
			$this->data['render'] = $contents;

		}

		if( isset( $_POST[ $this->data['form-title']  ] ) ){
			$this->save();
		}

	   	
	   	add_action( 'admin_menu', array( &$this, 'display' ) );

	    return $this;
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

	    if( $this->check && !$this->user->can( $this->capability ) ) return;

	    if( $this->data['options']['parent'] === false ){



	    	add_options_page( 
	    		$this->data['title'], 
	    		$this->data['options']['menu_title'], 
	    		$this->capability, 
	    		$this->data['slug'], 
	    		$this->data['render']
	    	);


	    }else{

	    	$parentSlug = $this->data['options']['parent'];

	    	if( substr( $parentSlug, -4 ) !== '.php' )
	    		$parentSlug = 'edit.php?post_type='.$parentSlug;

	    	add_submenu_page( 
	    		$parentSlug, 
	    		$this->data['title'],
	    		$this->data['options']['menu_title'],
	    		$this->data['options']['capability'],
	    		$this->data['slug'],
	    		$this->data['render']
	    	);
	    }

	}


	/**
	 * Call by "add_meta_box", build the HTML code.
	 *
	 * @param \WP_Post $post The WP_Post object.
	 * @param array $datas The settings page $args and associated fields.
	 * @throws SettingPAgeException
	 * @return void
	 */
	public function render() {

		$this->setDefaultValue();

		echo '<div class="wrap">';

			echo '<h2>'.$this->data['title'].'</h2>';
	   		echo '<br/><br/>';

	   		echo '<form method="post">';

	   		echo '<input type="hidden" name="'.$this->data['form-title'].'" value="true"/>';

	    	// Add nonce fields
	    	wp_nonce_field( Session::nonceAction, Session::nonceName );
	
	    	foreach( $this->data['fields'] as $field ){
	
	    		$field->render();
	
	    	}
	
	
	    	//render the javascript-templates seperate, to prevent doubles
	    	$rendered = array();
	
	    	foreach( $this->data['fields'] as $field ){
	
	    		if( method_exists( $field, 'renderTemplate' ) && !in_array( $field->name, $rendered ) ){
	
	    			echo $field->renderTemplate();
	    			$rendered[] = $field->name;
	
	    		}
	    	}	


	    	echo '<div class="button-wrapper">';

	    		echo '<input type="submit" class="button button-primary button-large" value="'.__( 'Save settings', 'cuisine' ).'">';

	    	echo '</div>';
	    	echo '</form>';

	    echo '</div>';
	}


	/**
	 * The wrapper install method. Save container values.
	 *
	 * @param int $postId The post ID value.
	 * @return void
	 */
	public function save(){


	    $nonceName = (isset($_POST[Session::nonceName])) ? $_POST[Session::nonceName] : Session::nonceName;
	    if (!wp_verify_nonce($nonceName, Session::nonceAction)) return;

	    // Check user capability.
	    if ( $this->check )
	        if ( !$this->user->can( $this->capability ) ) return;


	    $fields = array();

	    // Loop through the registered fields.
	    $fields = $this->data['fields'];
	    $fields = apply_filters( 'cuisine_before_settings_field_save', $fields, $this );
	    
	    $this->register( $fields );

	}


	/**
	 * Register the settings page and its fields into the DB.
	 *
	 * @param int $postId
	 * @param array $fields
	 * @return void
	 */
	private function register( $fields ) {
	    
	    $save = array();

	    foreach( $fields as $field ){

	       	$value = isset( $_POST[ $field->name ] ) ? $_POST[ $field->name ] : '';
	       	$save[ $field->name ] = $value;
	    
	    }

	    update_option( $this->data['slug'], $save );

	}


	/**
	 * Check settings page options: context, priority.
	 *
	 * @param array $options The settings page options.
	 * @return array
	 */
	private function parseOptions(array $options) {

	    return wp_parse_args( $options, array(

	        'menu_title'   	=> $this->data['title'],
	        'parent'		=> false,
	        'capability'	=> 'manage_options'

	    ));
	
	}


	/**
	 * return the name of these options
	 * 
	 * @return string
	 */
	private function getOptionName(){
		return 'settings-'.sanitize_title( $this->data['title'] );
	}


	/**
	 * Set the default 'value' property for all fields.
	 *
	 * @return void
	 */
	private function setDefaultValue() {
	    
		$values = get_option( $this->data['slug'], array() );


	    foreach ( $this->data['fields'] as $field ){

	        // Check if saved value
	        if( isset( $values[ $field->name] ) ){
	        	$value = $values[ $field->name ];
	        	$field->properties['defaultValue'] = $value;
	        }

	    }
	}

}

