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
		}



	}

	if( !is_admin() )
		\Cuisine\Front\Assets::getInstance();
