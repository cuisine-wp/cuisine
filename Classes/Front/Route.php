<?php
namespace Cuisine\Utilities;

class Route {

	/**
	 * All urls registered
	 * 
	 * @var array
	 */
	private $urls = array();

	/**
	 * All templates registered
	 * 
	 * @var array
	 */
	private $templates = array();

	/**
	 * All the arguments for this rout
	 * 
	 * @var array
	 */
	private $args = array();



	function __construct( $args = array() ){

		global $Cuisine;

		$this->urls = $Cuisine->routes;

		$this->templates = $Cuisine->templates;

		$this->args = $this->sanitizeArgs( $args );
	}


	/**
	 * Add a new Route
	 *
	 * @return void
	 */
	public function add(){

		if( isset( $this->args['url'] ) )
			$this->url();

		if( isset( $this->args['templates'] ) )
			$this->template();

	}

	/**
	 * Remove a route
	 *
	 * @param  string $key name for the route to be removed
	 * @return bool
	 */
	public function remove( $key ){

		if( isset( $this->urls[ $key ] ) )
			unset( $this->urls[ $key ] );

		if( isset( $this->templates[ $key ] ) )
			unset( $this->templates[ $key ] );

		$this->save();
	}


	/**
	 * Add a route purely on url
	 *
	 * @return void
	 */
	public function url(){

		$key = $this->getKey();

		$settings = array(
			'url'			=>		$this->args['url'],
			'post_type'		=>		$this->args['post_type'],
			'single'		=>		$this->args['single']
		);

		$this->urls[ $key ] = $settings;

		$this->save();
	}


	/**
	 * Add a template route
	 *
	 * @return void
	 */
	public function template(){

		$key = $this->getKey();
		$this->templates[ $key ] = $this->args['templates'];

		$this->save();
	}


	/**
	 * Get the key for an object
	 * 
	 * @return string
	 */
	private function getKey(){

		$key = $this->args['post_type'];
		if( isset( $this->args['name'] ) )
			$key .= '-'.$this->args['name'];

		if( $this->args['single'] == true )
			$key .= '-single';

		return $key;
	}


	/**
	 * Sets the defaults for the args array
	 * 
	 * @param  array $args 
	 * @return array
	 */
	private function sanitizeArgs( $args ){

		if( !isset( $args['post_type'] ) )
			$args['post_type'] = 'post';

		if( !isset( $args['single'] ) )
			$args['single'] = false;


		return $args;
	}


	/**
	 * Saves the urls and templates to the cuisine globals
	 * 
	 * @return void
	 */
	private function save(){

		$Cuisine->routes = $this->urls;
		$Cuisine->templates = $this->templates;
	}


}
