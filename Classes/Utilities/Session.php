<?php
namespace Cuisine\Session;

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

}