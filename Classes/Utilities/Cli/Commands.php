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


    	function forms( $args, $assoc_args ){

    		update_option( 'existingForms', array() );

    		// Print a success message
    		WP_CLI::success( "All auto-generated forms cleared." );

    	}
		
	
	
	}


	WP_CLI::add_command( 'cuisine', 'Cuisine\Utilities\Cli\CliCommands' );

