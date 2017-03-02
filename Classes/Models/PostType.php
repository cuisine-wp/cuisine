<?php
namespace Cuisine\Models;

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
     * Plural of this post type name
     *
     * @var string
     */
    public $plural;

    /**
     * Singular version of this post type name
     *
     * @var string
     */
    public $singular;

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
	    $this->plural = $plural;
        $this->singular = $singular;

        $this->properties['slug'] = $slug;
	    $this->properties['args'] = $this->setDefaultArguments();


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

        if( isset( $params['labels'] ) ){
            $params['labels'] = array_merge(
                $this->getDefaultLabels(),
                $params['labels']
            );
        }

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
	 * @return array
	 */
	private function setDefaultArguments(){

        $labels = $this->getDefaultLabels();

        $defaults = array(
            'label'                 => __( $this->plural, 'cuisine' ),
            'labels'                => $labels,
            'description'           => '',
            'public'                => true,
            'menu_position'         => 40,
            'has_archive'           => true,
            'show_in_rest'          => true,
            'rest_base'             => sanitize_title( $this->plural ),
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'              => array( 'title', 'editor', 'thumbnail' )
        );

        return $defaults;
    }

    /**
     * Returns an array of default labels
     *
     * @return array
     */
    public function getDefaultLabels()
    {
        return array(
            'name'                  => __( $this->plural, 'cuisine' ),
            'singular_name'         => __( $this->singular, 'cuisine' ),
            'add_new'               => __( 'Add New', 'cuisine' ),
            'add_new_item'          => __( 'Add New '. $this->singular, 'cuisine' ),
            'edit_item'             => __( 'Edit '. $this->singular, 'cuisine' ),
            'new_item'              => __( 'New ' . $this->singular, 'cuisine' ),
            'view_item'             => __( 'View ' . $this->singular, 'cuisine' ),
            'search_items'          => __( 'Search ' . $this->singular, 'cuisine' ),
            'not_found'             => __( 'No '. $this->singular .' found', 'cuisine' ),
            'not_found_in_trash'    => __( 'No '. $this->singular .' found in Trash', 'cuisine' ),
            'all_items'             => __( 'All ' . $this->plural, 'cuisine' ),
            'archives'              => __( 'Archives', 'cuisine' ),
            'insert_into_item'      => __( 'Insert into '.$this->singular, 'cuisine' ),
            'uploaded_to_this_item' => __( 'Upload to '.$this->singular, 'cuisine' ),
            'featured_image'        => __( 'Featured image', 'cuisine' ),
            'set_featured_image'    => __( 'Set featured image', 'cuisine' ),
            'remove_featured_image' => __( 'Remove featured image', 'cuisine' ),
            'use_featured_image'    => __( 'Use as featured image', 'cuisine' ),
            'menu_name'             => __( $this->plural, 'cuisine' ),
            'filter_items_list'     => __( 'Filter '.$this->plural, 'cuisine' ),
            'name_admin_bar'        => __( $this->singular, 'cuisine' ),
            'parent_item_colon'     => '',
            'items_list_navigation' => '',
            'items_list'            => ''
        );
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
