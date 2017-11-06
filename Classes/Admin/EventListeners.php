<?php

	namespace Cuisine\Admin;

	use \Cuisine\Utilities\Url;
    use \Cuisine\Wrappers\Flash;
    use \Cuisine\Wrappers\StaticInstance;

	class EventListeners extends StaticInstance{

        /**
         * Flash messages
         */
        protected $flash;
        

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();
            $this->flash = new FlashMessages();
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
            

            //on init
			add_action( 'admin_init', function(){
                //check if cuisine has recently been activated:
                $activated = get_option( 'cuisine_activated', false );
                if( $activated )
                    ( new PluginHandler() )->activate();

                //show notification:
                if( isset( $_GET['cuisine_installed'] ) )
                    $this->flash->message( __( 'Cuisine installed successfully.', 'cuisine' ) );

            
            });


            /**
             * Show flash messages:
             */
            add_action( 'admin_notices', function(){
                $this->flash->display();
			});
            

          

		}





	}

	if( is_admin() )
		\Cuisine\Admin\EventListeners::getInstance();
