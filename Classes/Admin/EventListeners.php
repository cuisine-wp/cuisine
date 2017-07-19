<?php

	namespace Cuisine\Admin;

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

			/**
			 * Add custom user capabilities
			 */
			
			add_action( 'init', function(){

				$roles = [ 'editor' => get_role( 'editor'), 'administrator' => get_role( 'administrator') ];
				$roles = apply_filters( 'cuisine_field_roles', $roles );

				foreach( $roles as $key => $role ){
					if( !is_null( $role ) )
						$role->add_cap( 'edit_fields' );
				}

			});

		}





	}

	if( is_admin() )
		\Cuisine\Admin\EventListeners::getInstance();
