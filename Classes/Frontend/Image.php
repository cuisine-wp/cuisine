<?php
namespace Cuisine\Frontend;

user Cuisine\Utilities\Url;

class Image {


	/*=============================================================*/
	/**             GET FUNCTIONS                                  */
	/*=============================================================*/

	/**
	 * Displays an image with lazyloading and retina-ready
	 * 
	 * @param  String $url      image-url
	 * @param  Array $supports  default: array( 'desktop', 'tablet', 'mobile' )
	 * @param  Array  $args     default empty
	 * @return void
	 */	
	public static function get( $url, $supports = null, $args = array() ){

		if( $url == null || $url == '' ) return false;

		if( $supports == null )
			$supports = self::getSupported();


		$class = self::getImageClasses( $supports, $args['class'] );
		$extension = self::getImageExtension( $url );
		$src = self::getDefaultSrc();

		$title = '';
		$alt = '';
		

		if( isset( $args['title'] ) ) $title = ' title="'.$args['title'].'"';
		if( isset( $args['alt'] ) ) $alt = ' alt="'.$args['alt'].'"';


		$html = '<img src="'.$src.'" data-src="'.$url.'" ';
		$html .= 'data-extension="'.$extension.'" ';
		$html .= 'class="'.$class.'" ';
		$html .= $title;
		$html .= $alt;
		$html .= '/>';

		return $html;

	}

	/**
	 * Get the url of a thumbnail of a post
	 * 
	 * @param  String $size
	 * @param  Int $pid 	$post_id
	 * @return String
	 */
	public static function getThumbnailUrl( $size = 'thumbnail', $pid = null ){

		if( $pid == null && !isset( $_GLOBALS['post'] ) ){
			throw new Exception("You need to specify a post ID or place this function in the Loop.");
			return false;
		}

		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), $size );
		return apply_filters( 'cuisine_image_url', $src[0] );

	}


	/**
	 * Get the url of an attachment
	 * 
	 * @param  Int $pid 	$post_id
	 * @param  String $size
	 * @return String
	 */
	public static function getMediaUrl( $pid, $size = 'thumbnail' ){

		if( $pid == null ){
			throw new Exception("You need to provide a valid post_id");
			return false;
		}

		$src = wp_get_attachment_image_src( $pid, $size );
		return apply_filters( 'cuisine_image_url', $src[0] );
	}


	/*=============================================================*/
	/**             ECHO FUNCTIONS                                 */
	/*=============================================================*/


	/**
	 * Echo results from get()
	 * 
	 * @param  String $url      image-url
	 * @param  Array $supports  default: array( 'desktop', 'tablet', 'mobile' )
	 * @param  Array  $args     default empty
	 * @return void
	 */
	public static function display( $url, $supports = null, $args = array() ){

		echo self::get( $url, $supports, $args );

	}

	/**
	 * Echo the results from getThumbnailUrl()
	 * 
	 * @param  String $size
	 * @param  Int $pid 
	 * @return void
	 */
	public static function thumbnailUrl( $size = 'thumbnail', $pid = null ){

		echo self::getThumbnailUrl( $size, $pid );

	}

	/**
	 * Echo the results from getMediaUrl()
	 * 
	 * @param  Int $pid 
	 * @param  String $size
	 * @return void
	 */
	public static function mediaUrl( $pid, $size ){

		echo self::getMediaUrl( $size, $pid );

	}



	/*=============================================================*/
	/**             SUPPORT FUNCTIONS                              */
	/*=============================================================*/

	/**
	 * Get the classes for an image, based on supported screens and 
	 * @param  Array $supports
	 * @param  Array  $args 
	 * @return String classes
	 */
	public static function getImageClasses( $supports, $args = array() ){

		$classes = array( 'lazy-img', 'img' );


		if( isset( $args['class'] ) ){

			if( !is_array( $args['class'] ) )
				$args['class'] = explode( ' ', $args['class'] );

			$classes = array_merge( $classes, $args['class'] );
		} 

		
		if( in_array( 'desktop', $supports ) )
			$classes[] = 'desktop-visible';
	
		if( in_array( 'mobile', $supports ) )
			$classes[] = 'mobile-visible';

		if( in_array( 'tablet', $supports ) )
			$classes[] = 'tablet-visible';

		if( in_array( 'retina', $supports ) )
			$classes[] = 'retina-visible';


		$classes = apply_filters( 'cuisine_image_classes', $classes );
		return implode( ' ', $classes );

	}

	/**
	 * Returns the default url for the src attribute
	 * @return String
	 */
	function getDefaultSrc(){

		$url = Url::theme( 'images', true ).'none.gif';
		$url = apply_filters( 'cuisine_default_src', $url );

		return $url;
	}


	/**
	 * Get the extension of an image
	 * @param  String $url
	 * @return String $extension
	 */
	function getImageExtension( $url ){

		$ex = substr( $url, -4 );

		switch( $ex ){

			case 'jpeg':
				return 'jpg';
				break;
			default:
				return substr( $ex, 1, 4 );
				break;

		}

	}


	/**
	 * Return the default image-support array
	 * @return Array
	 */
	public static function getSupported(){
		return array( 'desktop', 'tablet', 'mobile' );
	}

}
