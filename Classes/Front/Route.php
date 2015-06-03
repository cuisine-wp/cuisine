<?php
	
	namespace Cuisine\Front;
	
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
		 * All the arguments for this route
		 * 
		 * @var array
		 */
		private $args = array();
	
	
	
		function __construct(){
	
			global $Cuisine;
	
			$this->urls = $Cuisine->routes;
			$this->templates = $Cuisine->templates;
	
		}
	
	
		/**
		 * Set both a url + template 
		 * 
		 * @param string  $post_type        
		 * @param string  $url              
		 * @param string  $overview Overview Template Name
		 * @param string  $detail Detail Template Name (optional)
		 */
		public function add( $post_type, $url, $overview, $detail = false ){
	
			static::url( $post_type, $url, false );
			static::template( $post_type, $overview, $detail );
	
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
		 * @return bool Succes
		 */
		public function url( $post_type, $url, $detail = false ){
			
			$key = $url;
			$value = array(

					'post_type'	=> $post_type,
					'overview'	=> $url,
					'detail'	=> $detail

			);


			//is this route already set?
			if( !isset( $this->urls[ $key ] ) ){

				$this->urls[ $key ] = $value;
				$this->save();
	
				return true;
			}
	
			return false;
		}
	
	
		/**
		 * Add a template route
		 *
		 * @param  string $post_type
		 * @param  string $overview Overview Template Name
		 * @param  mixed $detail  Detail Template Name / false
		 * @return bool Succes
		 */
		public function template( $post_type, $overview, $detail = false ){
	
			//is this route already set?
			if( !isset( $this->templates[ $post_type ] ) ){
	
				$fallback = $post_type.'-detail';
				$templates = array(
	
						'overview'		=> 		$overview,
						'detail'		=>		( $detail ? $detail : $fallback )
	
				);
	
	
				$this->templates[ $post_type ] = $templates;
				$this->save();
	
				return true;
			}
	
			return false;
	
		}
		
	
		/**
		 * Saves the urls and templates to the cuisine globals
		 * 
		 * @return void
		 */
		private function save(){
			
			global $Cuisine;
			$Cuisine->routes = $this->urls;
			$Cuisine->templates = $this->templates;

		}
	}
