<?php
namespace Cuisine\View;

class Excerpt {

	/**
	 * Get a custom excerpt
	 * 
	 * @param  string  $string  the string to be cut short
	 * @param  integer $limit  amount of words
	 * @param  string  $break  delimeter
	 * @param  string  $pad    addendum
	 * @return string (altered)
	 */
	public static function get( $string, $limit = 0, $break = '.', $pad = '...' ){

		$string = strip_tags($string);
		$string = strip_shortcodes( $string );

		
		if( $limit == 0 ){

			if( isset($cuisine_excerpt_args['length'] ) ){

				$limit = $cuisine_excerpt_args['length']; 

			}else{

				$limit = 120;

			}
		}

		// return with no change if string is shorter than $limit
		if(strlen($string) <= $limit) return $string;

		// is $break present between $limit and the end of the string?
		if( ( $breakpoint = strpos( $string, $break, $limit ) ) !== false ) {
		
			if( $breakpoint < strlen( $string ) - 1 ) {
				$string = substr( $string, 0, $breakpoint ) . $pad;
    		}
		}
		
		return $string;
	}


    /**
     * Get the first paragraph out of some html:
     *
     * @param string $string
     * @return string
     */
    public static function intro( $string )
    {
        $string = explode( '</p>', $string );
        return $string[0].'</p>';
    }

	/**
	 * Get the content before the --more-- tag
	 * 
	 * @param  string  $string
	 * @return string/bool
	 */
	public static function beforeMore( $content, $format = true ){
	                
		$string = explode( '<!--more-->', $content );

		if( count( $string ) > 1 ){
			$string = $string[0];

			if( $format )
				$string = apply_filters( 'the_content', $string );

			return $string;
		}

		
		return false;
	}


	/**
	 * Get the content after the --more-- tag
	 * 
	 * @param  string  $string
	 * @return string/bool
	 */
	public static function afterMore( $content, $format = true ){

		$string = explode( '<!--more-->', $content );

		if( count( $string ) > 1 ){
	    	return $string[1];

	    	if( $format )
	    		$string = apply_filters( 'the_content', $string );

	    	return $string;
	    }

	    return false;

	}

}
