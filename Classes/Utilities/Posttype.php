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
            'name' 					=> __( $plural, 'cuisine' ),
			'singular_name' 		=> __( $singular, 'cuisine' ),
			'add_new' 				=> __( 'Add New', 'cuisine' ),
			'add_new_item' 			=> __( 'Add New '. $singular, 'cuisine' ),
			'edit_item' 			=> __( 'Edit '. $singular, 'cuisine' ),
			'new_item' 				=> __( 'New ' . $singular, 'cuisine' ),
			'view_item' 			=> __( 'View ' . $singular, 'cuisine' ),
			'search_items' 			=> __( 'Search ' . $singular, 'cuisine' ),
			'not_found' 			=> __( 'No '. $singular .' found', 'cuisine' ),
			'not_found_in_trash' 	=> __( 'No '. $singular .' found in Trash', 'cuisine' ),
			'all_items'  			=> __( 'All ' . $plural, 'cuisine' ),
			'archives' 				=> __( 'Archives', 'cuisine' ),
			'insert_into_item' 		=> __( 'Insert into '.$singular, 'cuisine' ),
			'uploaded_to_this_item' => __( 'Upload to '.$singular, 'cuisine' ),
			'featured_image' 		=> __( 'Featured image', 'cuisine' ),
			'set_featured_image' 	=> __( 'Set featured image', 'cuisine' ),
			'remove_featured_image' => __( 'Remove featured image', 'cuisine' ),
			'use_featured_image' 	=> __( 'Use as featured image', 'cuisine' ),
			'menu_name' 			=> __( $plural, 'cuisine' ),
			'filter_items_list' 	=> __( 'Filter '.$plural, 'cuisine' ),
			'name_admin_bar' 		=> __( $singular, 'cuisine' ),
			'parent_item_colon' 	=> '',
			'items_list_navigation' => '',
			'items_list' 			=> ''
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


    /**
     * Return the post-type's name (not slug)
     * 
     * @param  string $post_type
     * @return string Plural label of this Post Type
     */
    public function name( $post_type ){

    	$post_type = get_post_type_object( $post_type );
    	if( is_object( $post_type ) ) 
    		return $post_type->labels->name;

    	return false;
    }

    /**
     * Return the default template name for this post-type
     * 
     * @param  string $post_type
     * @return string Plural version of this Post Type, sanitized for file-usage.
     */
    public function template( $post_type ){

    	return sanitize_title( self::name( $post_type ) );
    	
    }


    /**
     * Get the public post types
     * 
     * @return array
     */
    public static function get(){

    	$post_types = get_post_types();

    	$array = array();
    	$hidden = array( 'revision', 'nav_menu_item' );
    	foreach( $post_types as $pt ){
    		if( !in_array( $pt, $hidden ) )
    			$array[] = $pt;
    	}

    	return $array;

    }

}
