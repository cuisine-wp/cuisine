<?php
	namespace Cuisine\Utilities\Cli;

	use WP_CLI;
	use WP_CLI_Command;
	use Cuisine\Wrappers\Sass;

	class CliCommands extends WP_CLI_Command{
	
	
		function sass( $args, $assoc_args ) {

			$action = 'refresh';
			if( isset( $assoc_args['ignore'] ) )
				$action = 'ignore';

			if( isset( $assoc_args['unignore'] ) )
				$action = 'unignore';


			switch( $action ){

        		case 'refresh':

					Sass::resetFiles(); 
    	    		update_option( 'registered_sass_files', array() );
	
        			WP_CLI::success( "Sass-files refreshed." );


        		break;
        		case 'ignore':

        			update_option( 'cuisine_ignore_sass', true );

        			WP_CLI::success( "Cuisine will ignore Sass." );

        		break;
        		case 'unignore':

        			update_option( 'cuisine_ignore_sass', false );

        			WP_CLI::success( "Cuisine will use Sass." );

        		break;
        	}
    	}


    	function forms( $args, $assoc_args ){

    		update_option( 'existingForms', array() );

    		// Print a success message
    		WP_CLI::success( "All auto-generated forms cleared." );

    	}
		
	
	
	}


	WP_CLI::add_command( 'cuisine', 'Cuisine\Utilities\Cli\CliCommands' );

