<?php
namespace Cuisine\Front;

use Cuisine\Utilities\Sort;
use Cuisine\Utilities\Url;

class Scripts {

	/**
	 * Array of registered scripts
	 * 
	 * @var array
	 */
	var $registered = array();



	/**
	 * Init events & vars
	 */
	function __construct(){

		global $Cuisine;

		//all registered scripts are stored in the Cuisine global
		$this->registered = $Cuisine->scripts;

	}

	/**
	 * Register a single script
	 * 
	 * @param  string $script script-name
	 * @param  string $url    url
	 * @return [type]         [description]
	 */
	public function register( $script, $url, $autoload = false ){

		global $Cuisine;

		//clean up the url
		$url = $this->sanitizeUrl( $url );

		$this->registered[ $script ] = array( 

			'name'		=> 		$script,
			'url'		=>		$url,
			'autoload'	=>		$autoload

		);

		$Cuisine->scripts = $this->registered;
	}


	/**
	 * Return the scripts as a JSON object
	 * 
	 * @return string (html, echoed)
	 */
	public function set(){

		//set the variables first:
		$this->setVars();

		$url = Url::plugin( 'cuisine', true ).'Assets/js';
		$config = $url.'/Front';
		$require = $url.'/libs/require.js';

		//load all JS with RequireJS
		echo '<script data-main="'.$config.'" src="'.$require.'"></script>';
	}


	/**
	 * Echoe the scripts as JSON objects
	 *
	 * @return string (html, echoed)
	 */
	private function setVars(){

		$url = esc_url_raw( Url::theme( 'js' ) );
		$scripts = $this->get();
		$autoload = $this->getAutoload();

		$jsVars = array(
			'baseUrl'	=> $url,
			'scripts' 	=> $scripts,
			'load'		=> $autoload
		);

		//make it filterable
		$jsVars = apply_filters( 'cuisine_js_vars', $jsVars );

		echo '<script>';
			//setup the Cuisine JS Object
			echo 'var Cuisine = '.json_encode( $jsVars );
		echo '</script>';
	}


	/**
	 * Make a URL of a script file load-ready.
	 * 
	 * @param  string $url
	 * @return string
	 */
	private function sanitizeUrl( $url ){

		//remove .js extension for RequireJS
		if( substr( $url, -3 ) === '.js' )
			$url = substr( $url, 0, -3 );

		//remove protocol
		$url = str_replace( array( 'http:', 'https:' ), '', $url );

		return $url;
	}


	/**
	 * Return the scripts as an Array
	 * 
	 * @return array
	 */
	public function get(){

		$scripts = $this->registered;
		return Sort::pluck( $scripts, 'url', true );

	}


	/**
	 * Return the names of autoload scripts
	 * 
	 * @return array
	 */
	public function getAutoload(){

		$array = array();
		$scripts = $this->registered;

		foreach( $scripts as $key => $script ){

			if( $script['autoload'] )
				$array[] = $key;

		}

		return $array;

	}


	/**
	 * Default analytics shtick:
	 * 
	 * @return string (html, echoed)
	 */
	public function analytics( $code ){

		echo "<script>
    			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
    			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
    			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
    			e.src='//www.google-analytics.com/analytics.js';
    			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    			ga('create','".$code."','auto');ga('send','pageview');
			</script>";
	}

}


