<?php

	namespace Cuisine\Admin;

	use \Cuisine\Wrappers\StaticInstance;
	use \Cuisine\Utilities\Url;


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

				
			add_action( 'admin_init', function(){
				
				global $pagenow;

				if( $pagenow == 'page.php' || $pagenow == 'page-new.php' ){
					wp_enqueue_media();
				}

			});

			add_action( 'admin_menu', function(){

				$url = Url::plugin( 'cuisine', true ).'Assets';
				wp_enqueue_script( 
					'cuisine_media', 
					$url.'/js/Media.js', 
					array( 'backbone', 'media-editor' )
				);

				wp_enqueue_script( 
					'cuisine_media_field', 
					$url.'/js/MediaField.js',
					array( 'backbone', 'media-editor' )
				);
				
			});
		}
	}


	if( is_admin() )
		\Cuisine\Admin\Assets::getInstance();