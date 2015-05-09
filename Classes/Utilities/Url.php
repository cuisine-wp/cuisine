<?php
namespace Cuisine\Utilities;

class Url {


	/**
	 * Get the url of a folder in the current theme
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function theme( $folder, $trailingslash = false ){

		

	}


	/**
	 * Get the url of a plugin
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function plugin( $name, $trailingslash = false ){


	}


	/**
	 * Get the path of the current theme or a plugin
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function path( $type, $trailingslash = false ){


	}


	/**
	 * End an url with a trailing slash 
	 * @param  String $url
	 * @return String
	 */
	public static function slashit( $url ){

		return trailinslashit( $url );

	}


}