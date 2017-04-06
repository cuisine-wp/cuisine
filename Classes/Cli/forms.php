<?php
	namespace Cuisine\Cli;

	use WP_CLI;
	use WP_CLI_Command;
	use Cuisine\Wrappers\Sass;

	class FormCommands extends WP_CLI_Command{
	
		/**
		 * Reset forms command
		 * 
		 * @param  array $args
		 * @param  array $assoc_args
		 * 
		 * @return WP_CLI::success message
		 */
    	function regenerate( $args, $assoc_args ){

    		update_option( 'existingForms', array() );

    		// Print a success message
    		WP_CLI::success( "All auto-generated forms cleared." );

    	}
		
	
	
	}


	WP_CLI::add_command( 'forms', 'Cuisine\Cli\FormCommands' );

