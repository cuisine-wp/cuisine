<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\StaticInstance;
	use Cuisine\Wrappers\PostType;
	use Cuisine\Utilities\Sort;
	use Cuisine\Utilities\Url;

	class TemplateEngine extends StaticInstance{

		/**
		 * Default folder
		 *
		 * @var string
		 */
		private $folder;


		/**
		 * Init events & vars
		 */
		function __construct(){

			//set the folder string:
			$this->folder = apply_filters( 'cuisine_template_location', 'pages/' );

			//setup the events
			$this->listen();

		}


		/**
		 * Set the events for this request
		 *
		 */
		private function listen(){

			add_filter( 'template_include', array( &$this, 'findTemplate' ) );

		}


		/**
		 * Find the right template for this request
		 *
		 * @return string ( path to template file )
		 */
		public function findTemplate( $include ){

			global $Cuisine, $post;

			$registered = $Cuisine->templates;
			$templates = array();
			$post_type = get_post_type();

			//check if a page-template has been set:
			$themePath = Url::path( 'theme' );
			$includeSlug = str_replace( $themePath, '', $include );

			if( is_page_template( $includeSlug ) )
				return $include;

			//catch 404 errors:
			if( is_404() ){

				$fourOhFourTemplate = apply_filters( 'cuisine-404-template', $this->folder.'404.php' );
				return locate_template( array( $fourOhFourTemplate, 'index.php' ) );

			}


			//check if we have custom template wishes:
			if( isset( $registered[ $post_type ] ) ){

				$type = 'overview';

				if( is_single() )
					$type = 'detail';


				$templates[] = $registered[ $post_type ][ $type ];

			}

			if( !empty( $templates ) ){
				//there are custom templates, add .php & $this->folder to 'em:
				$templates = Sort::prependValues( $templates, $this->folder );
				$templates = Sort::appendValues( $templates, '.php' );

			}


			//if there's no template available, return to defaults:
			$defaults = $this->getDefaults();

			//merge the two:
			$templates = array_merge( $templates, $defaults );



			//Loop through the templates and return it when found:
			if( !empty( $templates ) ){

				$new_include = locate_template( $templates );
				if( $new_include != '' )
					$include = $new_include;

			}

			return $include;
		}


		/**
		 * Get the default templates
		 *
		 * @return array
		 */
		private function getDefaults(){

			global $post;
			$templates = array();
			$post_type = get_post_type();
			$excluded = apply_filters(
				'cuisine_template_exclude_post_types',
				array()
			);


			//get default templates
			if( !in_array( $post_type, $excluded ) ){

				if( is_single() || is_page() ){

					//default: templates/page-{postname}.php
					//second: templates/page.php
					//third: templates/detail.php
					$templates = array(
									$this->folder.$post_type.'-'.$post->post_name.'.php',
									$this->folder.$post_type.'.php',
									$this->folder.'detail.php'
					);

				}else{

					$template = PostType::template( $post_type );

					//default: templates/portfolio.php
					//second: templates/overview.php

					if( $template )
						$templates[] = $this->folder.$template.'.php';

					$templates[] = $this->folder.'overview.php';

				}

			}else if( is_404() ){



			}

			return $templates;
		}


	}


	if( !is_admin() )
		\Cuisine\Front\TemplateEngine::getInstance();