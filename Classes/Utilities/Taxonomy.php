<?php
namespace Cuisine\Utilities;

class Taxonomy {

	/**
	 * Store the taxonomy data.
	 *
	 * @var DataContainer
	 */
	private $properties;

	
	/**
	 * Build a Taxonomy instance.
	 *
	 * @param array $properties The taxonomy properties.
	 */
	public function __construct( array $properties = array() ){

	    $this->properties = $properties;
	    
	}

	/**
	 * @param string $slug The taxonomy slug name.
	 * @param string|array $postType The taxonomy object type slug: 'post', 'page', ...
	 * @param string $plural The taxonomy plural display name.
	 * @param string $singular The taxonomy singular display name.
	 * @return \Cuisine\Utilities\Taxonomy
	 */
	public function make( $slug, $postType, $plural, $singular ){
	    
	    // Store properties.
	    $this->properties['slug'] = $slug;
	    $this->properties['postType'] = (array) $postType;
	    $this->properties['args'] = $this->setDefaultArguments( $plural, $singular );

	    return $this;

	}

	/**
	 * Set the custom taxonomy. A user can also override the
	 * arguments by passing an array of taxonomy arguments.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 * @param array $params Taxonomy arguments to override defaults.
	 * @return \Cuisine\Utilities\Taxonomy
	 */
	public function set( array $params = array() ){
	  
	    // Override custom taxonomy arguments if given.
	    $this->properties['args'] = array_merge($this->properties['args'], $params);

	    $this->register();
	  
	    return $this;
	}


	/**
	 * Triggered by the 'init' action/event.
	 * Register the custom taxonomy.
	 *
	 * @return void
	 */
	public function register(){

	    register_taxonomy($this->properties['slug'], $this->properties['postType'], $this->properties['args']);

	}


	/**
	 * Link the taxonomy to its custom post type. Allow the taxonomy
	 * to be found in 'parse_query' or 'pre_get_posts' filters.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
	 * @return \Cuisine\Utilities\Taxonomy
	 */
	public function bind(){

	    foreach ($this->properties['postType'] as $objectType){

	        register_taxonomy_for_object_type($this->properties['slug'], $objectType);
	    
	    }

	    return $this;
	}


	/**
	 * Set the taxonomy default arguments.
	 *
	 * @param string $plural The plural display name.
	 * @param string $singular The singular display name.
	 * @return array
	 */
	private function setDefaultArguments($plural, $singular){

	    $labels = array(
	        'name' => _x( $plural, 'cuisine' ),
	        'singular_name' => _x( $singular, 'cuisine' ),
	        'search_items' =>  __( 'Search ' . $plural, 'cuisine' ),
	        'all_items' => __( 'All ' . $plural, 'cuisine' ),
	        'parent_item' => __( 'Parent ' . $singular,'cuisine' ),
	        'parent_item_colon' => __( 'Parent ' . $singular . ': ' ,'cuisine' ),
	        'edit_item' => __( 'Edit ' . $singular,'cuisine' ),
	        'update_item' => __( 'Update ' . $singular,'cuisine' ),
	        'add_new_item' => __( 'Add New ' . $singular,'cuisine' ),
	        'new_item_name' => __( 'New '. $singular .' Name' ,'cuisine' ),
	        'menu_name' => __( $plural ,'cuisine' )
	    );

	    $defaults = array(
	        'label' 		=> __( $plural, 'cuisine' ),
	        'labels' 		=> $labels,
	        'public'		=> true,
	        'query_var'		=> true
	    );

	    return $defaults;
	}

}
