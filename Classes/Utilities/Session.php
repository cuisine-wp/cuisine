<?php
namespace Cuisine\Utilities;

class Session{

	/**
	 * Action identifier for a nonce field
	*/
	const nonceAction = 'cuisine-nonce-action';

	/**
	 * Name attribute for a nonce field
	*/
	const nonceName = '_cuisinenonce';
	
	/**
	 * Private constructor. Avoid building instances using the
	 * 'new' keyword.
	 */
	private function __construct(){

	}

	/**
	 * Get the current POST ID, no matter where you at.
	 * 
	 * @return mixed
	 */
	public static function postId(){

		global $post;

		if( isset( $_GET['post'] ) )
			return $_GET['post'];

		if( isset( $_POST['post_ID'] ) )
			return $_POST['post_ID'];

		if( isset( $post ) && isset( $post->ID ) )
			return $post->ID;

		return false;

	}


	/**
	 * Get the post ID based on the WP root query
	 * Else, default back to self::postId()
	 * 
	 * @return mixed
	 */
	public static function rootPostId(){

		global $wp_the_query;

		if( isset( $wp_the_query->post->ID ) )
			return $wp_the_query->post->ID;

		return self::postId();
		
	}


	/**
     * Check if there's a crawler:
     * 
     * @return boolean 
     */
    public static function isCrawler(){

        $user = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '' );
        $agents = 'Google|msnbot|Rambler|LinkedIn Bot|Twitterbot|Twitterbot/1.0|facebookexternalhit/1.1|facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
            
        if ( strpos( $agents , $user ) === false )
            return false;

        return true;
    }


}