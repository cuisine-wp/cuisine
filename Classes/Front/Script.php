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
	 * Array of registered JS vars
	 * 
	 * @var array
	 */
	var $jsVars = array();



	/**
	 * Init events & vars
	 */
	function __construct(){

		global $Cuisine;

		//all registered scripts are stored in the Cuisine global
		$this->registered = $Cuisine->scripts;

		$this->jsVars = $Cuisine->jsVars;

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
	 * Add a variable to the JS Cuisine global
	 * 
	 * @param string
	 * @param array
	 */
	public function variable( $id, $array ){

		global $Cuisine;

		$this->jsVars[ $id ] = $array;

		$Cuisine->jsVars = $this->jsVars;
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

		//allow registers to be overwritten:
		do_action( 'cuisine_js_override' );

		$url = esc_url_raw( Url::theme( 'js' ) );
		$scripts = $this->get();
		$autoload = $this->getAutoload();

		$jsVars = array(
			'siteUrl'	=> esc_url_raw( get_site_url() ),
			'baseUrl'	=> $url,
			'ajax'		=> admin_url('admin-ajax.php'),
			'scripts' 	=> $scripts,
			'load'		=> $autoload
		);

		//make it filterable
		$jsVars = apply_filters( 'cuisine_js_vars', $jsVars );

		echo '<script>';
			//setup the Cuisine JS Object
			echo 'var Cuisine = '.json_encode( $jsVars ).';';

			//setup the vars:
			foreach( $this->jsVars as $id => $val ){
				echo 'var '.ucwords( $id ).' = '.json_encode( $val ).';';
			}

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

		$scripts = apply_filters( 'cuisine_scripts', $this->registered );
		return Sort::pluck( $scripts, 'url', true );

	}


	/**
	 * Return the names of autoload scripts
	 * 
	 * @return array
	 */
	public function getAutoload(){

		$array = array();
		$scripts = apply_filters( 'cuisine_scripts', $this->registered );

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

		$string = "\n<script>
		 var _gaq = _gaq || [];
		 _gaq.push(['_setAccount', '".$code."']);
		 _gaq.push(['_trackPageview']);
		 (function() {
		   var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		   ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		 })();
		</script>";

		echo preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "\n", $string));
	}


	
}


