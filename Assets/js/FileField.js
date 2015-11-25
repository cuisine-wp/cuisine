/**
 * File field class
 *
 * Takes care of the javascripts functions in the Field engine.
 *
 * @since Cuisine 1.4
 */


 	var FileField = Backbone.View.extend({

		items: {},
		id: '',
		highestId: '',
		container: '',


		events: {

			'click #select-file' : 'launchMediaLibrary',
			'click #remove-file' : 'emptyFile'
		
		},
		 	
		initialize: function(){

		 	var self = this;
		 	self.id = self.$el.data('id');

		 	self.toggleRemoveButton();

		},	

		/**
		 * Show a media lightbox
		 * 
		 * @return void
		 */
		launchMediaLibrary: function( evt ){

			evt.preventDefault();
				

			var self = this;

			var options = {
				title:'Uploaden',
				button:'Opslaan',
				//media_type:'image',
				multiple:false,
				self: self,	
			}


			Media.uploader( options, function( attachment, options ){
					
				self.$el.find( '#file-id' ).val( attachment.id );
				self.$el.find( '#title').val( attachment.title );
				self.$el.find( '#url').val( attachment.url );
				self.$el.find( '#mime-type').val( attachment.subtype );
				self.$el.find( '#icon').val( attachment.icon );
				
				//console.log(attachment);
				//set the remove-btn
				self.toggleRemoveButton();

			});
		},

		/**
		 * Empty an image:
		 * 
		 * @return void
		 */
		emptyFile: function(){

			var self = this;

			self.$el.find( '#file-id' ).val( '' );
			self.$el.find( '#title').val( '' );
			self.$el.find( '#url').val( '' );
			self.$el.find( '#mime-type').val( '' );
			self.$el.find( '#icon').val( '' );

			//set the remove-btn
			self.toggleRemoveButton();
		},

		/**
		 * [toggleImage description]
		 * @return {[type]} [description]
		 */
		toggleRemoveButton: function(){

			var self = this;
			var id = self.$el.find('#file-id').val();

			if( id === '' ){
				self.$el.find('.remove-file-source').hide();
			}else{
				self.$el.find('.remove-file-source').show();
			}

		},

		destroy : function() {
   			this.undelegateEvents();
  		}

	});



	var _fileFields = [];


 	jQuery( document ).ready( function(){

 		cuisineInitFileFields();

 	});

 	function cuisineInitFileFields(){

 		//remove event references:
 		if( _fileFields.length > 0 ){
 			for( var i = 0; _fileFields.length > i; i++ ){
 				_fileFields[ i ].destroy();
 			}

 		}
		


 		_fileFields = [];

 		jQuery( '.file-field').each( function( index, obj ){
 			var filef = new FileField({ el: obj });
 			_fileFields.push( filef );
 		})
 	}