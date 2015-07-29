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

}