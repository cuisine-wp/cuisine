<?php
namespace Cuisine\Front;

/**
 * A few shortcodes to make our lives easier
 */
class Shortcodes {

	/**
	 * Shortcodes instance.
	 *
	 * @var \Cuisine\Front\Shortcodes
	 */
	private static $instance = null;


	/**
	 * Init events & vars
	 */
	function __construct(){

		//setup the events
		$this->setShortcodes();

	}

	/**
	 * Init the Shortcodes class
	 *
	 * @return \Cuisine\View\Shortcodes
	 */
	public static function getInstance(){

	    if ( is_null( static::$instance ) ){
	        static::$instance = new static();
	    }
	    return static::$instance;
	}


	/**
	 * Set the events for this request
	 *
	 * @return void
	 */
	private function setShortcodes(){

		add_shortcode( 'intro', array( &$this, 'makeIntro' ) );
		add_shortcode( 'break', array( &$this, 'makeBreak' ) );
		add_shortcode( 'line', array( &$this, 'makeLine' ) );

	}


	/**
	 * Intro shortcode 
	 * 
	 * @param  array $atts     Attributes
	 * @param  string $content
	 * @return string
	 */
	public function makeIntro( $atts, $content = null ){
		
		$html = '<div class="intro">';

			$html .= do_shortcode( wpautop( $content ) );

		$html .= '</div>';

		return $html;
	}


	/**
	 * Break shortcode 
	 * 
	 * @param  array $atts     Attributes
	 * @param  string $content
	 * @return string
	 */
	public function makeBreak( $atts, $content = null ){

		return '<hr class="break no-line">';

	}


	/**
	 * Line shortcode 
	 * 
	 * @param  array $atts     Attributes
	 * @param  string $content
	 * @return string
	 */
	public function makeLine( $atts, $content = null ){

		return '<hr class="break line">';

	}


}

if( !is_admin() )
	\Cuisine\Front\Shortcodes::getInstance();
