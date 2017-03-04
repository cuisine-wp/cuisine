<?php
	namespace Cuisine\Cli;

	use WP_CLI;
	use WP_CLI_Command;
	use Cuisine\Wrappers\Sass;

	class SassCommands extends WP_CLI_Command{
	

		/**
		 * Refreshes the Sass files
		 * 
		 * @param  Array $args       
		 * @param  Array $assoc_args
		 *  
		 * @return WP_CLI::success()         
		 */
		public function refresh( $args, $assoc_args )
		{

			Sass::resetFiles(); 
    	    update_option( 'registered_sass_files', array() );
	
        	WP_CLI::success( "Sass-files refreshed." );

    	}

    	/**
    	 * Ignore sass pipeline
		 * 
		 * @param  Array $args       
		 * @param  Array $assoc_args
		 * 
    	 * @return WP_CLI::success
    	 */
    	public function ignore( $args, $assoc_args  )
    	{
    		update_option( 'cuisine_ignore_sass', true );
        	WP_CLI::success( "Cuisine will ignore Sass." );		
    	}

    	/**
    	 * Unignore sass pipeline
    	 *
    	 * @param  Array $args       
		 * @param  Array $assoc_args
		 * 
    	 * @return WP_CLI::success
    	 */
    	public function unignore( $args, $assoc_args )
    	{
    		update_option( 'cuisine_ignore_sass', false );
        	WP_CLI::success( "Cuisine will use Sass." );
    	}
	
	}


	WP_CLI::add_command( 'cuisine sass', 'Cuisine\Cli\SassCommands' );

