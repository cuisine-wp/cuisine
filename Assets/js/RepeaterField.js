/**
 * Main Fields JS Class
 *
 * Takes care of the javascripts functions in the Field engine.
 *
 * @since Cuisine 1.4
 */


 	var RepeaterField = Backbone.View.extend({

 		id: '',
 		highestId: '',
 		template: '',
 		items: {},


 		events: {

 			'click .repeat-controls .plus' : 'addItem',
 			'click .repeat-controls .min' : 'removeItem'
 		},
 		 	
 		initialize: function(){

 		 	var self = this;
 		 	self.highestId = parseInt( self.$el.attr( 'data-highest-id' ) );
 		 	self.setItemEvents();
 		 	self.setItems();
 		 	self.setItemPositions();
 		},


 		addItem: function( e ){

 			var target = jQuery( e.target ).parent().parent();
 			var self = this;

 			var _temp = _.template( jQuery( '#'+self.$el.data( 'template' ) ).html() );
 			var template = _temp({ highest_id: self.highestId });

 			target.after( template );

 			self.highestId += 1;
 			self.$el.attr( 'data-highest-id', self.highestId );

 			self.setItems();
 			self.setItemPositions();

 			refreshFields();

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
 		 * Sets the sorting event for this repeaterfield
 		 *
 		 * @return void
 		 */
 		setItemEvents: function(){
 			
 			var self = this;

 			self.$el.sortable({
 				placeholder: 'repeater-placeholder',
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
 		cuisineInitRepeaterFields();
 	});

 	//refresh:
 	jQuery( document ).on( 'refreshFields', function(){
 		cuisineInitRepeaterFields();
 	})


 	var _repeaters = [];



 	function cuisineInitRepeaterFields(){


 		if( _repeaters.length > 0 ){

 			for( var i = 0; _repeaters.length > i; i++ ){
 				_repeaters[ i ].destroy();

 			}

 		}

 		_repeaters = [];

 		jQuery('.repeater-field' ).each( function( index, obj ){
 			var rf = new RepeaterField( { el: obj } );
 			_repeaters.push( rf );
 		});
 	}