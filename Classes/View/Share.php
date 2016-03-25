<?php


	namespace Cuisine\View;


	class Share{


		/**
		 * Type of button
		 * 
		 * @var string
		 */
		private $type;


		/**
		 * Array of counts
		 * 
		 * @var array
		 */
		private $counts;

		/**
		 * Int of count with the current type
		 * 
		 * @var array
		 */
		private $count;


		/**
		 * Title of this post, encoded
		 * 
		 * @var string
		 */
		public $title;



		/**
		 * Link of this post, encoded
		 *
		 * @var string
		 */
		public $link;



		/**
		 * Url, differs with type
		 *
		 * @var string
		 */
		private $url = '';



		function __construct( $type = 'facebook' ){

			//get the counts
			$counts = Loop::field( 'social_counts' );

			if( empty( $counts['twitter'] ) ) $counts['twitter'] = 0;
			if( empty( $counts['facebook'] ) ) $counts['facebook'] = 0;
			if( empty( $counts['linkedin'] ) ) $counts['linkedin'] = 0;
			if( empty( $counts['pinterest'] ) ) $counts['pinterest'] = 0;
			$this->counts = $counts;

			$this->setType( $type );

			$this->title = urlencode( Loop::title() );
			$this->link = urlencode( Loop::link() );


		}


		/**
		 * Set the type & count of the current button
		 * 
		 * @param string $type
		 * @return void
		 */
		private function setType( $type ){

			$this->type = $type;
			$this->count = ( isset( $this->counts[ $type ] ) ? $this->counts[ $type ] : 0 );

		}


		/*=============================================================*/
		/**             Make the button                                */
		/*=============================================================*/


		private function render(){

			$html = '';

			$html .= '<a class="post-counter button '.$this->type.'" target="_blank" ';
			$html .= $this->datas();
			$html .= '>';

				$html .= $this->icon();

				$html .= $this->count();

			$html .= '</a>';

			return $html;
		}


		/**
		 * Get all data attributes
		 * 
		 * @return void
		 */
		private function datas(){

			$html  = 'data-type="'.$this->type.'" ';
			$html .= 'data-href="'.$this->url.'" ';
			$html .= 'data-postid="'.Loop::id().'" ';
			$html .= 'data-count="'.$this->count.'"';

			return $html;

		}


		/**
		 * Show count
		 * 
		 * @return string ( html )
		 */
		private function count(){

			return '<p class="count">'.$this->count.'</p>';		

		}


		/**
		 * Get the right icon
		 * 
		 * @param  boolean $type
		 * @return string
		 */
		public function icon(){

			return '<i class="icon-'.$this->type.' fa fa-'.$this->type.'"></i>';

		}


		/*=============================================================*/
		/**             Button types                                   */
		/*=============================================================*/


		/**
		 * Generate a facebook button
		 * 
		 * @return string ( html, echoed )
		 */
		public function facebook(){

			$this->setType( 'facebook' );
			$this->url = 'https://www.facebook.com/share.php?u='.$this->link;

			return $this->render();
		}


		/**
		 * Generate a twitter button
		 * 
		 * @return string ( html, echoed )
		 */
		public function twitter(){

			$this->setType( 'twitter' );

			$this->url = 'https://www.twitter.com/intent/tweet?text=';
			$this->url .= $this->title.' - '.$this->link;

			return $this->render();
		}


		/**
		 * Generate a linkedin button
		 * 
		 * @return string ( html, echoed )
		 */
		public function linkedin(){

			$this->setType( 'linkedin' );

			$this->url = 'http://www.linkedin.com/shareArticle?mini=true&url=';
			$this->url .= $this->link.'&title='.$this->title;

			return $this->render();
		}


		/**
		 * Generate a pinterest button
		 * 
		 * @return string ( html, echoed )
		 */
		public function pinterest(){

			$this->setType( 'pinterest' );
			$this->url = 'http://pinterest.com/pin/create/link/?url='.$this->link;

			return $this->render();
		}



	}
