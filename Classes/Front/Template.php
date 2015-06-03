<?php
namespace Cuisine\Front;


class Template {

	/**
	 * Template instance.
	 *
	 * @var \Cuisine\Front\Template
	 */
	private static $instance = null;

	/**
	 * Default folder 
	 * 
	 * @var string
	 */
	private $folder;


	/**
	 * Init events & vars
	 */
	function __construct(){

		//set the folder string:
		$this->folder = apply_filters( 'cuisine_template_location', 'templates/' );

		//setup the events
		$this->setEvents();

	}

	/**
	 * Init the Template class
	 *
	 * @return \Cuisine\View\Template
	 */
	public static function getInstance(){

	    if ( is_null( static::$instance ) ){
	        static::$instance = new static();
	    }
	    return static::$instance;
	}


	/**
	 * Set the events for this request
	 * 
	 */
	private function setEvents(){

		add_filter( 'template_include', array( &$this, 'findTemplate' ) );
	
	}

	
	/**
	 * Find the right template for this request
	 * 
	 * @return string ( path to template file )
	 */
	public function findTemplate( $include ){

		global $Cuisine, $post;

		$registered = $Cuisine->templates;
		$templates = array();
		$post_type = get_post_type();

		//first see if there's a custom template present
		$key = ( is_single() ? $post_type.'-single' : $post_type );

		//if it's a single we can do a few things 
		//( {post_type}-{post_name} or {post_type}-single )
		if( is_single() ){

			$key = $post_type.'-'.$post->post_name;
			$altkey = $post_type.'-single';

			if( isset( $registered[ $key ] ) ){
				$templates[] = $registered[ $key ];
			
			}else if( isset( $registered[ $altkey ] ) ){
				$templates[] = $registered[ $altkey ];

			}

		}else if( isset( $registered[ $post_type ] ) ){

			$templates[] = $registered[ $post_type ];

		}

		//if there's no template available, return to defaults:
		if( empty( $templates ) )
			$templates = $this->getDefaults();

		//Loop through the templates and return it when found:
		if( !empty( $templates ) ){
			
			$new_include = locate_template( $templates );
			if( $new_include != '' )
				$include = $new_include;

		}

		return $include;
	}


	/**
	 * Get the default templates
	 * 
	 * @return array
	 */
	private function getDefaults(){

		global $post;
		$templates = array();
		$post_type = get_post_type();
		$excluded = apply_filters( 
			'cuisine_template_exclude_post_types', 
			array()
		);


		//get default templates
		if( !in_array( $post_type, $excluded ) ){

			if( is_single() || is_page() ){

				//default: templates/page-{postname}.php
				//second: templates/page.php
				//third: templates/detail.php

				$templates = array(
								$this->folder.$post_type.'-'.$post->post_name.'.php',
								$this->folder.$post_type.'.php',
								$this->folder.'detail.php'
				);

			}else{

				//refactor: PostType::template( $post_type );
				$post_type = get_post_type_object( $post_type );
				$name = sanitize_title( $post_type->labels->name ); 

				//default: templates/portfolio.php
				//second: templates/overview.php

				$templates = array(
								$this->folder.$name.'.php',
								$this->folder.'overview.php'
				);
			}

		}else if( is_404() ){



		}

		return $templates;
	}


}


if( !is_admin() )
	\Cuisine\Front\Template::getInstance();