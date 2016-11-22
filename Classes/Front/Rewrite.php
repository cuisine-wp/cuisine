<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\StaticInstance;

	class Rewrite extends StaticInstance{

		/**
		 * Array holding all custom registered routes
		 *
		 * @var array
		 */
		private $routes;


		/**
		 * Init events & vars
		 */
		function __construct(){

			//setup the events
			$this->listen();

		}

		/**
		 * Set the events for this request
		 *
		 */
		private function listen(){

			add_action( 'wp_loaded', function(){

				//filter the rewrite rules
				add_filter( 'rewrite_rules_array', array( &$this, 'setRules'), 100 );

				//filter urls
				add_filter( 'post_type_link', array( &$this, 'postPermalink' ), 10, 2 );

			});


			//flush on shutdown, in admin:
			if( is_admin() )
				add_action( 'shutdown', array( &$this, 'flush' ) );

		}


		/**
		 * Setup all custom rewrites via a filter
		 *
		 * @return array
		 */
		public function setRules( $rules ){

			global $Cuisine;

			//populate the routes array:
			$this->routes = $Cuisine->routes;

			//loop through all custom routes:
			if( !empty( $this->routes ) ){

				$newRules = array();

				foreach( $this->routes as $key => $args ){


					//if $args isn't an array, it's a post_type string
					if( !is_array( $args ) ){
						$post_type = $args;
						$url = $key;
						$detail = false;

					}else{
						$post_type = $args['post_type'];
						$url = $args['overview'];
						$detail = $args['detail'];
					}


					$query = 'index.php?post_type='.$post_type;
					$newRules[ $url.'/?$' ] = $query;
					$newRules[ $url.'?([0-9]{1,})/?$'] = $query.'&paged=$mates[1]';

					//set special url for detail-pages:
					if( $detail ){

						$query .= '&name=$matches[1]';
						$newRules[ $detail.'/([^/]+)' ] = $query;

					}
				}

				$rules = array_merge( $newRules, $rules );

			}

			return $rules;
		}



		/**
		 * Custom permalinks:
		 *
		 * @access public
		 * @param  string  $link
		 * @param  integer $id
		 * @return string (url)
		 */
		public function postPermalink( $link, $id = 0 ) {

			global $Cuisine;

			//populate the routes array:
			$this->routes = $Cuisine->routes;

			$post = get_post( $id );

			//check for erros:
			if ( is_wp_error( $post ) || empty( $post->post_name ) )
		    	return $link;

		    //loop through the routes
		    foreach( $this->routes as $url => $args ){

		    	//if args is an array:
		    	if( is_array( $args ) ){

		    		//and the post type matches:
		    		if( $args['post_type'] === $post->post_type ){

		    			//and the detail is set:
		    			if( isset( $args['detail' ] ) ){

		    				$url = $args['detail'].'/'.$post->post_name;
		    				$url = home_url( user_trailingslashit( $url ) );
		    				return $url;
		    			}
		    		}
		    	}
		    }

		    return $link;

		}


		/**
		 * Flush rewrites in the admin, on dev
		 *
		 * @access public
		 * @return void
		 */
		function flush(){

			global $wp_rewrite;
			$wp_rewrite->flush_rules();

		}


	}


	\Cuisine\Front\Rewrite::getInstance();