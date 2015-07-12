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

		}





	}

	if( is_admin() )
		\Cuisine\Admin\EventListeners::getInstance();
