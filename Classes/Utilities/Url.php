<?php
namespace Cuisine\Utilities;

class Url {


	/**
	 * Get the url of a folder in the current theme
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function theme( $folder = '', $trailingslash = false ){

		$url = get_stylesheet_directory_uri();
		$url .= self::themeSubDir( $folder );

		if( $trailingslash )
			$url = \trailingslashit( $url );

		return $url;

	}


	/**
	 * Get the parent-theme url of a folder
	 * @param  String $url
	 * @param  Bool slashit
	 * @return  String
	 */
	public static function parentTheme( $folder, $trailingslash = false ){

		$url = get_template_directory_uri();
		$url .= self::themeSubDir( $folder );

		if( $trailingslash )
			$url = \trailingslashit( $url );

		return $url;

	}	

	/**
	 * Returns the url of the theme directory with the custom folder
	 * @param  String  $folder 
	 * @param  boolean $trailingslashit 
	 * @return String
	 */
	public static function themeSubDir( $folder ){

		if( $folder != '' ){

			switch( $folder ){

				case 'sass':

					return '/css/sass';
					break;

				case 'vendors':

					return '/js/libs';
					break;

				case 'libs':

					return '/js/libs';
					break;
					
				default:

					return '/'.$folder;
					break;
			}
		}

		return '';

	}


	/**
	 * Get core WordPress includes urls
	 * 
	 * @param  string $folder
	 * @return string (url)
	 */
	public static function wp( $folder, $prefix = 'js' ){

		return includes_url( trailingslashit( $prefix ).$folder );

	}


	/**
	 * Get the url of a plugin
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function plugin( $name, $trailingslash = false ){

		$path = \trailingslashit( WP_PLUGIN_URL );
		$path .=  $name;

		if( $trailingslash )
			$path = \trailingslashit( $path );

		return $path;

	}


	/**
	 * Get the path of the current theme or a plugin
	 * @param  String $url
	 * @param  Bool slashit 
	 * @return String
	 */
	public static function path( $type, $folder = '', $trailingslash = false ){

		$path = '';
		switch( $type ){

			case 'theme':

				$path = \trailingslashit( get_template_directory() );
				$path .= $folder;
				break;

			case 'content':

				$path = \trailingslashit( WP_CONTENT_DIR );
				$path .= $folder;

				break;

			default:

				$path = \trailingslashit( WP_PLUGIN_DIR );
				$path .= $folder;

				break;	
		}


		if( $trailingslash )
			$path = \trailingslashit( $path );


		return $path;

	}

	/**
	 * Checks to see if the page is being server over SSL or not
	 *
	 * @return boolean
	 */
	public static function isHttps(){

		return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
	}

	/**
	 * Return the current URL.
	 *
	 * @return string
	 */
	public static function current(){

		$url = '';

		// Check to see if it's over https
		$is_https = self::isHttps();
		if ($is_https) {
		    $url .= 'https://';
		} else {
		    $url .= 'http://';
		}

		// Was a username or password passed?
		if (isset($_SERVER['PHP_AUTH_USER'])) {
		    $url .= $_SERVER['PHP_AUTH_USER'];

			if (isset($_SERVER['PHP_AUTH_PW'])) {
				$url .= ':' . $_SERVER['PHP_AUTH_PW'];
			}

			$url .= '@';
		}


		// We want the user to stay on the same host they are currently on,
		// but beware of security issues
		// see http://shiflett.org/blog/2006/mar/server-name-versus-http-host
		$url .= $_SERVER['HTTP_HOST'];

		$port = $_SERVER['SERVER_PORT'];

		// Is it on a non standard port?
		if ($is_https && ($port != 443)) {
		    $url .= ':' . $_SERVER['SERVER_PORT'];
		} elseif (!$is_https && ($port != 80)) {
		    $url .= ':' . $_SERVER['SERVER_PORT'];
		}

		// Get the rest of the URL
		if (!isset($_SERVER['REQUEST_URI'])) {
		    // Microsoft IIS doesn't set REQUEST_URI by default
		    $url .= $_SERVER['PHP_SELF'];

		    if (isset($_SERVER['QUERY_STRING'])) {
		        $url .= '?' . $_SERVER['QUERY_STRING'];
		    }
		} else {
			$url .= $_SERVER['REQUEST_URI'];
		}

		return $url;
	}


}