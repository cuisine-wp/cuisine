<?php
namespace Cuisine\View;

use Cuisine\Utilities\Url;
use Exception;

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

		$args 		= self::parseArgs( $args );
		$class 		= self::getImageClasses( $supports, $args['class'] );
		$extension 	= self::getImageExtension( $url );
		$src 		= self::getDefaultSrc();

		$title = '';
		$alt = '';
		

		if( $args['title'] ) $title = ' title="'.$args['title'].'"';
		if( $args['alt'] ) $alt = ' alt="'.$args['alt'].'"';


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
        if( $src !== false ){
	    	return apply_filters( 'cuisine_image_url', $src[0] );
	    }
    
        return apply_filters( 'cuisine_image_url', '' );
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
	 * 
	 * @param  Array $supports
	 * @param  Array  $args 
	 * @return String classes
	 */
	private static function getImageClasses( $supports, $args = array() ){

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
	 * 
	 * @return String
	 */
	private static function getDefaultSrc(){

		$url = Url::theme( 'images', true ).'none.gif';
		$url = apply_filters( 'cuisine_default_src', $url );

		return $url;
	}


	/**
	 * Get the extension of an image
	 * 
	 * @param  String $url
	 * @return String $extension
	 */
	public static function getImageExtension( $url ){

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
	 * Fill in the gaps, arguments wise
	 * 
	 * @return Array
	 */
	public static function parseArgs( $args ){

		if( !isset( $args['class'] ) )
			$args['class'] = array();

		if( !isset( $args['title'] ) )
			$args['title'] = false;

		if( !isset( $args['alt'] ) )
			$args['alt'] = false;

		return $args;
	}


	/**
	 * Return the default image-support array
	 * 
	 * @return Array
	 */
	private static function getSupported(){

		return array( 'desktop', 'tablet', 'mobile' );
	
	}

	/*=============================================================*/
	/**             SIZE FUNCTIONS                                 */
	/*=============================================================*/


	/**
	 * Add an image size
	 * 
	 * @param string   $name   
	 * @param integer  $width  
	 * @param integer  $height 
	 * @param boolean  $crop   (optional)
	 */
	public static function addSize( $name, $width, $height, $crop = true ){

		add_image_size( $name, $width, $height, $crop );

	}

		
	/**
	 * Add thumbnail support
	 *
	 * @return void
	 */
	public static function addSupport(){
		add_theme_support( 'post-thumbnails' );
	}

	public static function getImageHTML($postId, $size = 'full') {

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($postId), $size );
		$img_url = $thumb['0']; 

		$thumb_id = get_post_thumbnail_id();

		$thumbnail_src = get_post( $thumb_id );

		$img_caption = '';
		$img_title =  '';
		$img_description = '';
		$img_alt = '';

		if( isset($thumbnail_src) ){

			$img_caption =  $thumbnail_src->post_excerpt;
			$img_title =  $thumbnail_src->post_title;
			$img_description = $thumbnail_src->post_content;
			$img_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

			if( $img_alt !== '' ){
				$show_alt = 'alt="'.$img_alt.'"';
			}
			return '<img itemprop="image" src="'.$img_url.'" '.$show_alt.' title="'.$img_title.'" />';
		}

		return get_the_post_thumbnail($postId);
	}

}
