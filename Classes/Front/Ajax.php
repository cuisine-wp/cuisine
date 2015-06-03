<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\AjaxInstance;
	use stdClass;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();

		}

		/**
		 * All ajax events
		 * 
		 * @return string, echoed
		 */
		private function listen(){

			//creating a section:
			add_action( 'wp_ajax_socialShare', array( &$this, 'socialShare' ) );
			add_action( 'wp_ajax_nopriv_socialShare', array( &$this, 'socialShare' ) );

		}


		/**
		 * Update share counts
		 *  
		 * @return void
		 */
		public function socialShare(){

			$this->setPostGlobal();

			global $post;
			$type = $_POST['type'];

			$meta = get_post_meta( $pid, 'social_counts', true );
			if( empty( $meta['tw'] ) )  $meta['tw'] = 0;
			if( empty( $meta['fb'] ) )  $meta['fb'] = 0;
			if( empty( $meta['in'] ) )  $meta['in'] = 0;
			if( empty( $meta['pin'] ) ) $meta['pin'] = 0;
			if( empty( $meta['gp'] ) )  $meta['gp'] = 0;

			$shorts = array(
				'twitter'	=> 'tw',
				'facebook' 	=> 'fb',
				'linkedin'	=> 'in',
				'pinterest'	=> 'pin',
				'google'	=> 'gp'
			);

			$short = $shorts[ $type ];
			$meta[$short] = $meta[$short] + 1;

			update_post_meta( $post->ID, 'social_counts', $meta );

			echo 'success';
			die();
		}

	}


	\Cuisine\Front\Ajax::getInstance();

