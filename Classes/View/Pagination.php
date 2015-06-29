<?php


	namespace Cuisine\View;

	use WP_Query;
	use Cuisine\Utilities\Url;
	
	class Pagination {
	
		
		var $query;

		var $amount = 1;

		var $max = 1;

		var $current = 1;
	
		var $pages = 1;
		
		/**
		 * Setup the Pagination class 
		 * 
		 * @param WP_Query $_query
		 */
		function __construct( $_query = null ){
			
			//default to global if not set
			if( $_query == null ){
			
				global $wp_query;
				$_query = $wp_query;
			
			}


			$this->query = $_query;

			$this->amount = $_query->query_vars['posts_per_page'];
			$this->max = $_query->found_posts;
			$this->current = $_query->query_vars['paged'];

			if( $this->current == 0 )
				$this->current = 1;

			$this->pages = ceil( $this->max / $this->amount );

		}
	
		
		/**
		 * Display the current pagination
		 * 
		 * @return string (html, echoed)
		 */
		public function display(){

			echo self::get();

		}


		/**
		 * Returns the html for the current pagination
		 * 
		 * @return string ( html )
		 */
		public function get(){

			//we have pages:
			if( $this->pages > 1 ){

				$html = '';
				$html .= '<nav class="pages" itemscope="itemscope" itemtype="http://www.schema.org/SiteNavigationElement">';

					for( $i = 1; $i <= $this->pages; $i++ ){

						if( $i == $this->current ){

							$html .= '<span itemprop="name">'.$current.'</span>';

						}else{

							$url = self::getLink( $i );
							$html .= '<a  itemprop="url" href="'.$url.'">'.$i.'</a>';

						}

					}

				$html .= '</nav>';

				return $html;

			//we don't have pages:
			}else{
				
				return false;
			}

		}


		public function getLink( $num ){

			$url = Url::current();

			$pageString = apply_filters( 'cuisine_page_string', 'page' );


			$page = explode( '/'.$pageString, $url );
			$pageURL = trailingslashit( $page[0] );
			
			if( $num > 1 )
				$pageURL .= $pageString.'/'. $num;


			return $pageURL;
		}

	
	}
	