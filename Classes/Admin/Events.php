<?php

	namespace Cuisine\Admin;

	use \Cuisine\Utilities\Url;

	class Events{

		/**
		 * Sections bootstrap instance.
		 *
		 * @var \Cuisine
		 */
		private static $instance = null;


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->adminEnqueues();

		}

		/**gatherSections
		 * Init the framework classes
		 *
		 * @return \Cuisine
		 */
		public static function getInstance(){

		    if ( is_null( static::$instance ) ){
		        static::$instance = new static();
		    }
		    return static::$instance;
		}


		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function adminEnqueues(){

				
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
		\Cuisine\Admin\Events::getInstance();
