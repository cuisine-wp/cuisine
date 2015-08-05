<?php
	namespace Cuisine\Utilities\Cli;

	use WP_CLI;
	use WP_CLI_Command;
	use Cuisine\Wrappers\Sass;

	class CliCommands extends WP_CLI_Command{
	
	
		function sass( $args, $assoc_args ) {
        	
			Sass::resetFiles(); 
        	update_option( 'registered_sass_files', array() );

        	// Print a success message
        	WP_CLI::success( "Sass-files refreshed." );
    	}
		
	
	
	}


	WP_CLI::add_command( 'cuisine', 'Cuisine\Utilities\Cli\CliCommands' );

