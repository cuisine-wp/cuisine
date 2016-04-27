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


 		events: {

 			'click .repeat-controls .plus' : 'addItem',
 			'click .repeat-controls .min' : 'removeItem'
 		},
 		 	
 		initialize: function(){

 		 	var self = this;
 		 	self.highestId = parseInt( self.$el.data( 'highest-id' ) );

 		},


 		addItem: function( e ){

 			var target = jQuery( e.target ).parent().parent();
 			var self = this;

 			var _temp = _.template( jQuery( '#'+self.$el.data( 'template' ) ).html() );
 			var template = _temp({ highest_id: self.highestId });

 			target.after( template );

 			self.highestId += 1;
 			self.$el.attr( 'data-highest-id', self.highestId );

 			refreshFields();

 		},

 		removeItem: function( e ){

 			var target = jQuery( e.target ).parent().parent();
 			target.remove();

 			refreshFields();

 		}
	
 	});



 	jQuery( document ).ready( function(){

 		cuisineInitRepeaterFields();

 	});

 	function cuisineInitRepeaterFields(){
 		jQuery('.repeater-field' ).each( function( index, obj ){
 			var rf = new RepeaterField( { el: obj } );
 		});
 	}