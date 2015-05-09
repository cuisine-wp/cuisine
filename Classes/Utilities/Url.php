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

				default:

					return '/'.$folder;
					break;
			}
		}

		return '';

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



}