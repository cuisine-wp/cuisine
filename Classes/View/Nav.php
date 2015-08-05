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

				$class = ( $class ? $class.' ' : '' ) . $name;

				$args = array( 
					'theme_location' => $name,
					'items_wrap' => '<nav id="%1$s" class="%2$s"><ul>%3$s</ul></nav>',
					'depth' => $depth,
					'menu_class' => $class
				);

				wp_nav_menu( $args );	
			}

		}
			

		/**
		 * Displays a default menu-toggle button
		 * 
		 * @return String (html)
		 */
		public static function mobileToggle( $echo = true ){

			$toggle = apply_filters( 
				'cuisine_menu_toggle', 
				'<div class="menu-switch"><i class="fa fa-bars"></i></div>'
			);

			if( $echo )
				echo $toggle;

			return $toggle;

		}				


		/**
		 * Set a menu-item as active on different pages
		 * 
		 * @param array $args
		 */
		public static function setActive( $name, $args ){

			global $Cuisine;

			$args = self::getActiveArgs( $args );
			$Cuisine->navItems[ $name ] = $args;

		}


		/**
		 * Get the default arguments
		 * 
		 * @param  array $args
		 * @return array
		 */
		public static function getActiveArgs( $args ){

			if( !isset( $args['type'] ) )
				$args['type'] = 'single';

			if( !isset( $args['query'] ) )
				$args['query'] = 'post';

			return $args;

		}


	}
