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
	* @author github.com/mattytemple
	* 
	* @param  String time / data
	* @return  String relative time
	*/
	public static function relative( $ts ) {

		if( !ctype_digit( $ts ) ){
			$ts = strtotime($ts);
    	}

		$diff = time() - $ts;
		if( $diff == 0 ) {
		
			return 'now';
		
		} elseif( $diff > 0 ){
			$day_diff = floor($diff / 86400);

			if($day_diff == 0) {
			
			    if( $diff < 60 ) return __( 'zojuist', 'cuisine' );
			    if( $diff < 120 ) return __( '1 minuut geleden', 'cuisine' );
			    if( $diff < 3600 ) return floor( $diff / 60 ) . __( ' minuten geleden' );
			    if( $diff < 7200 ) return __( '1 uur geleden', 'cuisine' );
			    if( $diff < 86400 ) return floor( $diff / 3600 ) . __( ' uur geleden', 'cuisine' );
			
			}

			if( $day_diff == 1 ) { return __( 'Gisteren', 'cuisine' ); }
			if( $day_diff < 7 ) { return $day_diff . __( ' dagen geleden', 'cuisine' ); }
			if( $day_diff < 31 ) { return ceil($day_diff / 7) . __( ' weken geleden', 'cuisine' ); }
			if( $day_diff < 60 ) { return __( 'vorige maand', 'cuisine' ); }
			return self::get( $ts, 'F Y' );

		} else {
        
        	$diff = abs( $diff );
        	$day_diff = floor( $diff / 86400 );
        	if($day_diff == 0) {
            	if( $diff < 120 ) { return __( 'over een minuutje', 'cuisine' ); }
            	if( $diff < 3600 ) { return __( 'over ', 'cuisine' ) . floor( $diff / 60 ) . __( ' minuten', 'cuisine' ); }
            	if( $diff < 7200 ) { return __( 'over een uur', 'cuisine' ); }
            	if( $diff < 86400 ) { return __( 'over ', 'cuisine' ) . floor( $diff / 3600 ) . __( ' uur', 'cuisine' ); }
        	}

        	if( $day_diff == 1 ) { return __( 'Morgen', 'cuisine' ); }
        	if( $day_diff < 4 ) { return self::get( $ts, 'l' ); }
        	if( $day_diff < 7 + (7 - date( 'w' ) ) ) { return __( 'volgende week', 'cuisine' ); }
        	if( ceil($day_diff / 7 ) < 4 ) { return __( 'over ', 'cuisine' ) . ceil($day_diff / 7) . __( ' weken', 'cuisine' ); }
        	if( date('n', $ts) == date('n') + 1 ) { return __( 'volgende maand', 'cuisine' ); }
        	return self::get( $ts, 'F Y' );
	
		}
	}




}