<?php


	namespace Cuisine\View;

	class Nav{


		/**
		 * Register menus
		 * 
		 * @param  mixed $menus array / string
		 * @return void
		 */
		public static function register( $menus ){

			$register = array();

			if( !is_array( $menus ) )
				$menus = array( $menus );


			//loop through each menu and 
			foreach( $menus as $name ){

				$key = static::getLocation( $name );
				$register[ $key ] = $name;
			}

			register_nav_menus( $register );

		}


		/**
		 * Return the location of a menu
		 * 
		 * @param  string $name
		 * @return string $location
		 */
		public static function getLocation( $name ){

			return sanitize_title( $name ).'-nav';

		}


		/**
		 * Display a nav-menu
		 * 
		 * @param  string  $name
		 * @param  mixed $class string/boolean (optional)
		 * @param  integer $depth (optional)
		 * @return void
		 */
		public static function display( $name, $class = false, $depth = 2 ){

			$name = static::getLocation( $name );

			if( has_nav_menu( $name ) ){

				$args = array( 
					'theme_location' => $name,
					'items_wrap' => '<nav id="%1$s" class="%2$s"><ul>%3$s</ul></nav>',
					'depth' => $depth
				);

				if( $class )
					$args['menu_class'] = $class;

				wp_nav_menu( $args );	
			}

		}
							



	}
