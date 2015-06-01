<?php
namespace Cuisine\Utilities;

class PostType {


	/**
	 * Properties of this post type.
	 *
	 * @var array
	 */
	private $properties;


	/**
	 * The registered custom post type.
	 *
	 * @var Object
	 */
	private $postType;


	/**
	 * Build a custom post type.
	 *
	 * @param array $properties The post type properties.
	 */
	public function __construct( array $properties = array() ){

	    $this->properties = $properties;

	}


	/**
	 * Define a new custom post type.
	 *
	 * @param string $slug The post type slug name.
	 * @param string $plural The post type plural name for display.
	 * @param string $singular The post type singular name for display.
	 * @return \Cuisine\Utilities\PostType
	 */
	public function make( $slug, $plural, $singular ){

	    // Set main properties.
	    $this->properties['slug'] = $slug;
	    $this->properties['args'] = $this->setDefaultArguments( $plural, $singular );

	    return $this;
	}


	/**
	 * Set the custom post type. A user can also override the
	 * arguments by passing an array of custom post type arguments.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @param array $params The custom post type arguments.
	 * @return \Cuisine\Utilities\PostType
	 */
	public function set( array $params = array() ){

	    // Override custom post type arguments if given.
	    $this->properties['args'] = array_merge( $this->properties['args'], $params );

	    $this->register();

	    return $this;
	}


	/**
	 * Triggered by the 'init' action event.
	 * Register a WordPress custom post type.
	 *
	 * @return void
	 */
	public function register(){

		$this->postType = register_post_type( 
			$this->properties['slug'], 
			$this->properties['args']
		);

	}


	/**
	 * Set the custom post type default arguments.
	 *
	 * @param string $plural The post type plural display name.
	 * @param string $singular The post type singular display name.
	 * @return array
	 */
	private function setDefaultArguments( $plural, $singular ){

        $labels = array(
            'name' => __( $plural, 'cuisine' ),
            'singular_name' => __( $singular, 'cuisine' ),
            'add_new' => __( 'Add New', 'cuisine' ),
            'add_new_item' => __( 'Add New '. $singular, 'cuisine' ),
            'edit_item' => __( 'Edit '. $singular, 'cuisine' ),
            'new_item' => __( 'New ' . $singular, 'cuisine' ),
            'all_items' => __( 'All ' . $plural, 'cuisine' ),
            'view_item' => __( 'View ' . $singular, 'cuisine' ),
            'search_items' => __( 'Search ' . $singular, 'cuisine' ),
            'not_found' =>  __( 'No '. $singular .' found', 'cuisine' ),
            'not_found_in_trash' => __( 'No '. $singular .' found in Trash', 'cuisine' ),
            'parent_item_colon' => '',
            'menu_name' => __( $plural, 'cuisine' )
        );

        $defaults = array(
            'label' 		=> __( $plural, 'cuisine' ),
            'labels' 		=> $labels,
            'description'	=> '',
            'public'		=> true,
            'menu_position'	=> 40,
            'has_archive'	=> true,
            'supports'		=> array( 'title', 'editor', 'thumbnail' )

        );

        return $defaults;
    }

}
