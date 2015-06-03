<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\StaticInstance;
	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Scripts;


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
				Scripts::register( 'social-share', $url.'Share', false );
				
			});
		}



	}

	if( !is_admin() )
		\Cuisine\Front\Assets::getInstance();
