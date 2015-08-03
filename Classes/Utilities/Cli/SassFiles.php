<?php
	namespace Cuisine\Utilities\Cli;

	use WP_CLI;
	use WP_CLI_Command;

	class SassFiles extends WP_CLI_Command{
	
	
		function sass( $args, $assoc_args ) {
        	
        	update_option( 'registered_sass_files', array() );

        	// Print a success message
        	WP_CLI::success( "Sass-files cleared." );
    	}
		
	
	
	}


	WP_CLI::add_command( 'cuisine', 'Cuisine\Utilities\Cli\SassFiles' );

