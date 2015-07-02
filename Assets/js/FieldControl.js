
	function refreshFields(){


		setEditors();

		setMediaFields();


	}
	

	/**
	 * Re-init the content-editors
	 */
	function setEditors(){

		console.log( 'test' );
	
		jQuery( '.editor-wrapper' ).each( function( item ){

			var _id = jQuery( this ).data( 'id' );
			tinymce.execCommand('mceRemoveEditor', true, _id);
			var init = tinymce.extend( {}, tinyMCEPreInit.mceInit[ _id ] );
			try { tinymce.init( init ); } catch(e){}

		});
	}



	/**
	 * Re-init media-fields
	 * 
	 */
	function setMediaFields(){
		cuisineInitMediaFields();
	}