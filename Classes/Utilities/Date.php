<?php
namespace Cuisine\Utilities;

class Date {


	/**
	 * Echoes self::get
	 * 
	 * @param  string|date-object $date
	 * @param  string $format
	 * @return string (echoed)
	 */
	public static function display( $date = null, $format = 'j F Y' ){

		echo self::get( $date, $format );

	}


	/**
	 * Get the current date in the format, translated by WordPress
	 * 
	 * @param  string|date-object $date
	 * @param  string $format
	 * @return string
	 */
	public static function get( $date = null, $format = 'j F Y' ){

		global $post;

		if( $date == null ){
			$date_string = $post->post_date;

		}else{
			$date_string = date( 'Y-m-d H:i:s', $date );

		}

		$d = mysql2date( $format, $date_string );
		$date = apply_filters( 'get_the_date', $d, $format );
		
		return $date;

	}


	/**
	*	Create a relative time:
	*	
	* @access public
	* @param  String time / data
	* @return  String relative time
	*/
	function relative($date) {

		$diff = time() - strtotime( $date );
		if ( $diff<60){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('seconden geleden', 'cuisine');
			}else{
				return $diff .' '. __('seconde geleden', 'cuisine');
			}
		}
		
		$diff = round( $diff/60 );
		
		if ( $diff<60 ){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('minuten geleden', 'cuisine');
			}else{
				return $diff .' '. __('minuut geleden', 'cuisine');
			}
		}
		
		$diff = round( $diff/60 );

		if ( $diff<24 ){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('uren geleden', 'cuisine');
			}else{
				return $diff .' '. __('uur geleden', 'cuisine');
			}
		}
	
		$diff = round( $diff/24 );

		if ( $diff<7 ){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('dagen geleden', 'cuisine');
			}else{
				return $diff .' '. __('dag geleden', 'cuisine');
			}
		}
		
		$diff = round( $diff/7 );

		if ( $diff<4 ){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('weken geleden', 'cuisine');
			}else{
				return $diff .' '. __('week geleden', 'cuisine');
			}
		}

		$diff = round( $diff/30 );

		if ( $diff<4 ){
			if( Number::isPlural( $diff ) ){
				return $diff .' '. __('maanden geleden', 'cuisine');
			}else{
				return $diff .' '. __('maand geleden', 'cuisine');
			}
		}

	}




}