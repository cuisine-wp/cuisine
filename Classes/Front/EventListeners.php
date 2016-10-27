<?php

	namespace Cuisine\Front;

	use \Cuisine\Cron\Queue;
	use \Cuisine\Utilities\Url;
	use \Cuisine\Wrappers\StaticInstance;


	class EventListeners extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();


		}

		/**
		 * Listen for WordPress Hooks
		 *
		 * @return void
		 */
		private function listen(){


			//Add filters for current nav items:
			add_filter( 'nav_menu_css_class', function( $classes, $item ){

				global $Cuisine, $wp_query;

				foreach( $Cuisine->navItems as $name => $args ){

					if( strtolower( $name ) == strtolower( $item->title ) ){

						$addClass = false;

						if( $args['type'] == 'single' || $args['type'] == 'overview' ){

							//check the post-type
							if( get_post_type() == $args['query'] )
								$addClass = true;

						}else if( $args['type'] == 'page' && is_page() ){

							//check the page
							if( is_page( $args['query'] ) )
								$addClass = true;

						}else if( $args['type'] == 'taxonomy' ){

							//check if the current post has the taxonomy:
							if( is_single() ){

								$addClass = has_term( $args['value'], $args['query'] );

							}else{

								//check if we're looking at an archived page:
								if( isset( $wp_query->queried_object->taxonomy ) && $wp_query->queried_object->taxonomy == $args['query'] ){
									if( $wp_query->queried_object->slug == $args['value'] ){

										$addClass = true;

									}
								}

							}
						}


						//add the class
						if( $addClass )
							$classes[] = 'current-menu-item';

					}

				}

				return $classes;


			}, 100, 2 );

			/**
			 * Expand cron-options:
			 */
			add_filter( 'cron_schedules', function(){

				return Queue::getIntervals();

			});

		}





	}

	\Cuisine\Front\EventListeners::getInstance();
