/**
 * Main Fields JS Class
 *
 * Takes care of the javascripts functions in the Field engine.
 *
 * @since Cuisine 1.4
 */


 	var flexField = Backbone.View.extend({

 		id: '',
 		highestId: '',
 		template: '',
 		items: {},
 		maxItems: -1,
 		minItems: -1,


 		events: {

 			'click #add-layout' : 'showLayoutDropdown',
 			'click .add-layout' : 'addItem',
 			'click .repeat-controls .min' : 'removeItem'
 		},
 		 	
 		initialize: function(){

 		 	var self = this;
 		 	self.highestId = parseInt( self.$el.attr( 'data-highest-id' ) );
 		 	self.maxItems = parseInt( self.$el.attr('data-max-repeats' ) );
 		 	self.minItems = parseInt( self.$el.attr('data-min-repeats' ) );
 		 	self.setItemEvents();
 		 	self.setItems();
 		 	self.setItemPositions();
 		},

 		showLayoutDropdown: function( e ){
 			var self = this;
 			e.preventDefault();
 			self.$el.find('.layout-selector').toggleClass( 'active' );
 		},


 		addItem: function( e ){
 			

 			var self = this;
 			var layout = jQuery( e.target ).data( 'layout' );

 			self.$el.find('.layout-selector').removeClass( 'active' );

 			if( self.maxItems == -1 || self.maxItems >= ( self.items.length + 1 ) ){

	 			var _templateName = self.$el.data( 'template' )+'-'+layout;
	 			var _temp = _.template( jQuery( '#'+_templateName ).html() );
	 			var template = _temp({ highest_id: self.highestId });

	 			self.$el.find('.add-layout-button-wrapper').before( template );

	 			self.highestId += 1;
	 			self.$el.attr( 'data-highest-id', self.highestId );

	 			self.setItems();
	 			self.setItemPositions();

	 			refreshFields();

	 		}

 		},

 		removeItem: function( e ){

 			var self = this;
 			var target = jQuery( e.target ).parent().parent();
 			target.remove();

 			self.setItems();
 			self.setItemPositions();

 			refreshFields();

 		},



 		/**
 		 * Set the items object for this field:
 		 *
 		 * @return void
 		 */
 		setItems: function(){
 			
 			var self = this;
 			self.items = self.$el.find( '.repeatable' );

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
 		 * Sets the sorting event for this flexfield
 		 *
 		 * @return void
 		 */
 		setItemEvents: function(){
 			
 			var self = this;

 			self.$el.sortable({
 				placeholder: 'flex-placeholder',
 				handle: '.sort-pin',
 				update: function (event, ui) {

 					self.setItems();
 					self.setItemPositions();

 				}
 			});
 		},

 		destroy: function(){
 			this.undelegateEvents();
 		}
	
 	});


 	//start:
 	jQuery( document ).ready( function(){
 		cuisineInitflexFields();
 	});

 	//refresh:
 	jQuery( document ).on( 'refreshFields', function(){
 		cuisineInitflexFields();
 	})


 	var _flexs = [];



 	function cuisineInitflexFields(){


 		if( _flexs.length > 0 ){

 			for( var i = 0; _flexs.length > i; i++ ){
 				_flexs[ i ].destroy();

 			}

 		}

 		_flexs = [];

 		jQuery('.flex-field' ).each( function( index, obj ){
 			var rf = new flexField( { el: obj } );
 			_flexs.push( rf );
 		});
 	}