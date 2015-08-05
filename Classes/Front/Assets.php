<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\StaticInstance;
	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Script;


	class Assets extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->enqueues();

		}


		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){

			add_action( 'init', function(){

				//scripts:
				$url = Url::plugin( 'cuisine', true ).'Assets/js/';
				Script::register( 'social-share', $url.'Share', false );
				Script::register( 'cuisine-validate', $url.'Validate', false );	

			});



			//Add filters for current nav items:
			add_filter( 'nav_menu_css_class', function( $classes, $item ){

				global $Cuisine;

				foreach( $Cuisine->navItems as $name => $args ){

					if( strtolower( $name ) == strtolower( $item->title ) ){
	
						$addClass = false;
	
						if( $args['type'] == 'single' && is_single() ){
	
							//check the post-type
							if( get_post_type() == $args['query'] )
								$addClass = true;
	
						}else if( $args['type'] == 'page' && is_page() ){
	
							//check the page
							if( is_page( $args['query'] ) )
								$addClass = true;
	
						}
	
	
						//add the class
						if( $addClass )
							$classes[] = 'current-menu-item';
	
					}

				}

				return $classes;


			}, 100, 2 );

		}



	}

	if( !is_admin() )
		\Cuisine\Front\Assets::getInstance();
