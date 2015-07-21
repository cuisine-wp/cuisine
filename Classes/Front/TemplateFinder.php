<?php
namespace Cuisine\Front;

use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;

/**
 * The Template class locates templates
 * @package Cuisine\Front
 */
class TemplateFinder {


	/**
	 * The default template (usually in a plugin)
	 * 
	 * @var string
	 */
	private $default;


	/**
	 * String of the custom overwrite
	 * 
	 * @var string
	 */
	private $overwrite;




	/**
	 * Get a template in $filename
	 * 
	 * @param  string $filename
	 * @param  string $default
	 * @return \Cuisine\Front\TemplateFinder ( chainable )
	 */
	public function find( $filename, $default ){

		$this->default = $this->sanitizeDefault( $default );
		$this->overwrite = array( $filename );

		return $this;

	}


	/**
	 * Get a template in the /elements directory
	 * 
	 * @param  string $filename
	 * @param  string $default
	 * @return \Cuisine\Front\TemplateFinder ( chainable )
	 */
	public function element( $filename, $default ){

		$folder = apply_filters( 'cuisine_theme_elements_folder', 'elements' );
		$path = trailingslashit( $folder );

		$this->default = $this->sanitizeDefault( $default );
		$this->overwrite = array( $path.$filename );

		return $this;
	}

	/**
	 * Get a template in the /templates directory
	 * 
	 * @param  string $filename
	 * @param  string $default
	 * @return \Cuisine\Front\TemplateFinder ( chainable )
	 */
	public function template( $filename, $default ){

		$folder = apply_filters( 'cuisine_theme_templates_folder', 'templates' );
		$path = trailingslashit( $folder );

		$this->default = $this->sanitizeDefault( $default );
		$this->overwrite = array( $path.$filename );

		return $this;
	}


	/**
	 * Get a template in the /views directory
	 * 
	 * @param  string $filename
	 * @param  string $default
	 * @return \Cuisine\Front\TemplateFinder ( chainable )
	 */
	public function view( $filename, $default ){

		$folder = apply_filters( 'cuisine_theme_views_folder', 'views' );
		$path = trailingslashit( $folder );

		$this->default = $this->sanitizeDefault( $default );
		$this->overwrite = array( $path.$filename );

		return $this;
	}


	/**
	 * Set the defaults correctly
	 * @param mixed $default
	 */
	private function sanitizeDefault( $default ){

		if( substr( $default, -4 ) !== '.php' )
			$default .= '.php';

		return $default;

	}



	/**
	 * Include the found files
	 * 
	 * @return void
	 */
	public function display(){

		//check if the theme contains overwrites:
		$located = $this->checkTheme();

		//fall back on own templates:
		if( !$located )
			$located = $this->default;

		include( $located );

	}


	/**
	 * Check the theme for these files
	 * 
	 * @return located
	 */
	private function checkTheme(){


		$templates = Sort::appendValues( $this->overwrite, '.php' );
		$located = locate_template( $templates );

		return $located; 
	}




}