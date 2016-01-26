<?php
namespace Cuisine\Utilities {

	use DateTime;


	class Logger {

		const FileName = 'chef_cuisine.log';


		/**
		 * Logs an error in cuisine logfile
		 * 
		 * @param  string $message 
		 */
		public static function error( $message, $display = false ){

			self::make( $message, 'ERROR:', $logLevel );
		}

		/**
		 * Logs an message in cuisine logfile
		 * 
		 * @param  string $message 
		 */
		public static function message( $message, $display = false ){

			self::make( $message, 'MESSAGE:', $display );
		}

		/**
		 * Logs in cuisine logfile
		 * 
		 * @param  const $logType 
		 * @param  string $message
		 * @return const $logLevel
		 */
		private static function make( $message, $logType, $display = false ){

			// check if the constant is set in WP_Config
			if ( defined('CUISINE_DISPLAY_LOG') && CUISINE_DISPLAY_LOG )
				$displayLog = CUISINE_DISPLAY_LOG;
			else 
				$displayLog = $display;

			// check if the constant is set in WP_Config
			if ( defined('CUISINE_LOGLEVEL') ) {
				$logLevel = CUISINE_LOGLEVEL;
			} else {
				$logLevel = LogLevel::FUNCTIONNAMES;
			}

			// get stacktrace of functioncall
		    $backtrace = debug_backtrace();
		    $stacktrace = self::getStacktrace($backtrace, $logLevel);

			// get current timestamp
			$datetime = date( 'd-m-Y H:i:s' );

			// get user if available
			$user = '';
			if ( is_user_logged_in() ) {
		        $current_user = wp_get_current_user();
		        $user = '<span class="username"><label class="log_label">USER:</label>'.$current_user->user_login . '</span>';
		    }

		    // construct logstring
		    $logstring = '<div class="log_error"><span class="datetime">' . $datetime . '</span><span class="logtype"><label class="log_label">'. $logType . '</label>' . $message . '</span><ul class="stacktrace">' . $stacktrace . '</ul>' . $user . '</div>';
		    
		    if ( $displayLog ) {
		    	echo $logstring;
		    }

			self::log($logstring);			
		}


		/**
		* Gets the stacktrace of an log event
		*
		* @param array[][] $backtrace
		* @param LogLevel $logLevel
		*/
		public static function getStacktrace( $backtrace, $logLevel ) {
			
			// exclude some functions from log
			$excludedFunctions = array( 'make','__callStatic','include','require_once','require','call_user_func_array', 'error', 'message' );
			
			// initialize returnstring
			$tracestring = '';

			// reverse the trace for better readability
			$backtrace = array_reverse( $backtrace );
			
			$firstArray = true;

			// loop the stacktrace
			foreach ($backtrace as $trace) {
				//only log methods which are in the stacktrace from a file and a line
				if ( isset( $trace['function'] ) && !in_array( $trace['function'],$excludedFunctions ) && isset( $trace['file'] ) && isset( $trace['line'] ) ){
					$tracestring .= '<li class="method">';
					if($firstArray) {
						$tracestring .= '<span class="method_name"><label class="log_label">METHOD:</label>' . $trace['function'] .'</span>';
						$firstArray = false;
					} else {
						$tracestring .= '<span class="method_name"><label class="log_label sub_label">METHOD:</label>' . $trace['function'] .'</span>';
					}
 
					if ( isset($trace['class']) && $logLevel > 1 ) {
						$tracestring .= ' <span class="phpclass"><label class="log_label sub_label">IN CLASS:</label>' . $trace['class'] . '</span>';
					}
					// Log filename and linenumber
					if ( $logLevel > 2 ) {
						$tracestring .= ' <span class="filename"><label class="log_label sub_label">FILENAME:</label>' . $trace['file'] . '</span>';
						$tracestring .= ' <span class="linenumber"><label class="log_label sub_label">ON LINE:</label>' . $trace['line'] . '</span>';
					}
					$tracestring .= '</li>';
				}
				
			}
			return $tracestring;
		}

		/**
		* Make sure the dir exists and return complete filepath
		*/
		public static function getFilePath() {

			$filepath = WP_CONTENT_DIR . DS . 'logs';

		    if ( !is_dir( $filepath ) ){
		    	mkdir( $filepath );
		    }

		    $filepath .= DS . self::FileName;

		    return $filepath;
		}

		/**
		* Write the logstring
		*/
		public static function log( $logstring ) {
				
			// get the complete filepath
			$filepath = self::getFilePath();

			// fix markup
			$logstring = str_replace('<label class="log_label sub_label">METHOD:</label>', ' > ', $logstring);
			$logstring = str_replace(':</label>', ': ', $logstring);
			$logstring = str_replace('class="log_label">', 'class="log_label"> | ', $logstring);

			// strip the HTML tags
			$logstring = strip_tags($logstring);

			// add new line
			$logstring .= "\n\n";

			// write to file
		    file_put_contents( $filepath, $logstring, FILE_APPEND | LOCK_EX );
		}

	}

	/**
	* abstract class for different log levels
	*/
	abstract class LogLevel
	{
	    const FULL = 3;
	    const CLASSNAMES = 2;
	    const FUNCTIONNAMES = 1;
	}

}