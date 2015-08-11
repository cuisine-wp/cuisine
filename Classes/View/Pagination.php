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
		public function init( $_query = null ){
			
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
		public function display( $_query = null ){

			echo self::get( $_query );

		}


		/**
		 * Returns the html for the current pagination
		 * 
		 * @return string ( html )
		 */
		public function get( $_query = null ){

			$this->init( $_query );

			//we have pages:
			if( $this->pages > 1 ){

				$html = '';
				$html .= '<nav class="pages" itemscope="itemscope" itemtype="http://www.schema.org/SiteNavigationElement">';

					for( $i = 1; $i <= $this->pages; $i++ ){

						if( $i == $this->current ){

							$html .= '<span itemprop="name">'.$this->current.'</span>';

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



		/**
		 * Display the single next / prev buttons
		 *
		 * @param string $url
		 * @return 
		 */
		public function single( $url = false ){
			
			echo '<div class="single-nav">';
				
				previous_post_link( '<div class="link-wrapper prev">%link</div>', '<i class="fa fa-arrow-left"></i>' );

				if( $url )
					echo '<a class="overview" href="'.$url.'"><i class="fa fa-th"></i></a>';


				next_post_link( '<div class="link-wrapper pull-right next">%link</div>', '<i class="fa fa-arrow-right"></i>' );

			echo '</div>';
	
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
	