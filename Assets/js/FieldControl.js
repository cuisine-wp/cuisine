
	//first setups
	jQuery( document ).ready( function(){

		jQuery( '.datepicker' ).datepicker({
			firstDay: 1,
			dateFormat: "dd-mm-yy"
		});

		setEditors();

	});


	/**
	 * Refresh events + model-data for fields
	 * @return void
	 */
	function refreshFields(){

		setEditors();
		setMediaFields();
		setFileFields();

		//trigger the refresh-fields event for external plugins
		jQuery( document ).trigger( 'refreshFields' );

	}
	

	/**
	 * Re-init the content-editors
	 */
	function setEditors(){
	
		jQuery( '.editor-wrapper' ).each( function( item ){

			var _id = jQuery( this ).data( 'id' );
			tinyMCE.execCommand( 'mceRemoveEditor', true, _id);	

			var _settings =  tinyMCEPreInit.mceInit[ 'defaultEditor' ];
			if(  typeof( tinyMCEPreInit.mceInit[ _id ] ) != 'undefined' )
				_settings = tinyMCEPreInit.mceInit[ _id ];
			

			if( typeof( wp.editor ) == 'undefined' ){
				var init = tinyMCE.extend( {}, _settings );
				tinyMCE.init( init );
				tinyMCE.execCommand( 'mceAddEditor', false, _id );
			}else{
				//wp.editor.remove( _id );
				wp.editor.initialize( _id, { tinymce: _settings, quicktags: false });
			}

			
		});
	}



	/**
	 * Re-init media-fields
	 * 
	 */
	function setMediaFields(){
		cuisineInitMediaFields();
	}



	/**
	 * Re-init file-fields
	 * 
	 */
	function setFileFields(){
		cuisineInitFileFields();
	}