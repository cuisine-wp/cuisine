
	//first setups
	jQuery( document ).ready( function(){

		jQuery( '.datepicker' ).datepicker({
			firstDay: 1,
			dateFormat: "dd-mm-yy"
		});

	});


	/**
	 * Refresh events + model-data for fields
	 * @return void
	 */
	function refreshFields(){

		setEditors();
		setMediaFields();
		setFileFields();

	}
	

	/**
	 * Re-init the content-editors
	 */
	function setEditors(){
	
		jQuery( '.editor-wrapper' ).each( function( item ){

			var _id = jQuery( this ).data( 'id' );
			tinymce.execCommand('mceRemoveEditor', true, _id);
			tinymce.execCommand( 'mceAddEditor', false, _id );

			var init = tinymce.extend( {}, tinyMCEPreInit.mceInit[ _id ] );
			try { tinymce.init( init ); } catch(e){ console.log( e );}

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