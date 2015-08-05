/**
 * Media + Image field classes
 *
 * Takes care of the javascripts functions in the Field engine.
 *
 * @since Cuisine 1.4
 */


 	var MediaField = Backbone.View.extend({
	
		items: {},
		id: '',
		highestId: '',
		container: '',


		events: {

			'click #media-add' : 'launchMediaLibrary',
			'click .remove-btn' : 'removeMediaItem',
		},
 	
 		initialize: function(){

 			var self = this;
 			self.id = self.$el.data('id');
 			self.highestId = parseInt( self.$el.data( 'highest-id' ) );
 			
 			self.container = self.$el.find( '.media-inner' );
 			self.setItems();
 			self.setItemEvents();
 			self.setItemPositions();

 		},

 		/**
 		 * Set the items object for this field:
 		 *
 		 * @return void
 		 */
 		setItems: function(){
 			
 			var self = this;
 			self.items = self.$el.find( '.media-item' );

 			if( self.items.length > 0 ){
 				self.$el.find('.no-media').hide();
 			}else{
 				self.$el.find('.no-media').show();
 			}
 		
 		},

 		/**
 		 * Sets the sorting and deleting events for this mediafield
 		 *
 		 * @return void
 		 */
 		setItemEvents: function(){
 			
 			var self = this;

 			jQuery( self.container ).sortable({
 				placeholder: 'media-placeholder',
 				update: function (event, ui) {

 					self.setItems();
 					self.setItemPositions();

 				}
 			});
 		},

 		/**
 		 * Set item positions:
 		 *
 		 * @return void
 		 */
 		setItemPositions: function(){

 			var self = this;

 			for( var i = 0; i < self.items.length; i++ ){

 				var item = jQuery( self.items[ i ] );

 				//set the position:
 				item.find( '#position' ).val( i );

 			}

 		},


 		/**
 		 * Remove a media item:
 		 * 
 		 * @return void
 		 */
 		removeMediaItem: function( ev ){
 			ev.preventDefault();
 			var self = this;

 			if( confirm( "Weet je zeker dat je deze afbeelding wil verwijderen?" ) ){
 				jQuery( ev.target ).parent().parent().remove();

 				//set the new items object
 				self.setItems();
 					
 				//re-init events:
 				self.setItemEvents();

 				//set positions correctly:
 				self.setItemPositions();	
 			}
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
 				multiple:true,
 				self: self,	
 			}


 			var properties = {};
 			var fullId = self.fullId;
 			var _position = 200;

 			Media.uploader( options, function( attachments, options ){
 				
 				for( var i = 0; i < attachments.length; i++ ){

                    var attachment = attachments[ i ];
                    
                    var _preview = "";

                    //check if image is an SVG, if so the attachment does not have an sizes array (offcourse)
                    if(attachment.subtype && attachment.subtype.indexOf("svg") > -1) {
                        _preview = attachment.url;
                    } else {
                         _preview = attachment.sizes.full.url;

                        if( attachment.sizes.thumbnail !== undefined )
                            _preview = attachment.sizes.thumbnail.url;
                    }

                    var htmlTemplate = _.template( jQuery( '#media_item_template').html() );
                    var output = htmlTemplate({
                        item_id: self.getHighestId(),
                        preview_url: _preview,
                        img_id: attachment.id,
                        position: _position,
                    });


                    //jQuery( self.container ).append( output );
                    self.$el.find('.media-inner').append( output );

                    _position++;
                }

 				//set the new items object
 				self.setItems();
 					
 				//re-init events:
 				self.setItemEvents();

 				//set positions correctly:
 				self.setItemPositions();

 			});
 		},

 		/**
 		 * Gets the highest ID and upps it by one
 		 * 
 		 * @return void:
 		 */
 		getHighestId: function(){

 			var self = this;

 			self.highestId = parseInt( self.highestId ) + 1;
 			return self.highestId;

 		},


 		destroy : function() {
   			this.undelegateEvents();
  		}

	
 	});


	var ImageField = Backbone.View.extend({

		items: {},
		id: '',
		highestId: '',
		container: '',


		events: {

			'click #select-img' : 'launchMediaLibrary',
		
		},
		 	
		initialize: function(){

		 	var self = this;
		 	self.id = self.$el.data('id');

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
						
				var _full = ( attachment.sizes.full !== undefined ? attachment.sizes.full.url : '' );
				var _large = ( attachment.sizes.large !== undefined ? attachment.sizes.large.url : '' );
				var _medium = ( attachment.sizes.medium !== undefined ? attachment.sizes.medium.url : '' );
				var _thumbnail = ( attachment.sizes.thumbnail !== undefined ? attachment.sizes.thumbnail.url : '' );

				self.$el.find( '#img-id' ).val( attachment.id );
				self.$el.find( '#thumb').val( _thumbnail );
				self.$el.find( '#medium').val( _medium );
				self.$el.find( '#large').val( _large );
				self.$el.find( '#full').val( _full );
				self.$el.find( '#orientation' ).val( attachment.sizes.full.orientation );

				self.$el.find( '#preview' ).attr( 'src', _thumbnail );

			});
		},

		destroy : function() {
   			this.undelegateEvents();
  		}

	});


	var _mediaFields = [];
	var _imgFields = [];


 	jQuery( document ).ready( function(){

 		cuisineInitMediaFields();

 	});

 	function cuisineInitMediaFields(){

 		//remove event references:
 		if( _mediaFields.length > 0 ){
 			for( var i = 0; _mediaFields.length > i; i++ ){
 				_mediaFields[ i ].destroy();
 			}

 		}
		
		if( _imgFields.length > 0 ){
 			for( var i = 0; _imgFields.length > i; i++ ){
 				_imgFields[ i ].destroy();
 			}
 		}


 		_mediaFields = [];
 		_imgFields = [];
 		jQuery('.media-grid' ).each( function( index, obj ){

 			var mf = new MediaField( { el: obj } );
 			_mediaFields.push( mf );
 		});

 		jQuery( '.image-field').each( function( index, obj ){
 			var imgf = new ImageField({ el: obj });
 			_imgFields.push( imgf );
 		})
 	}