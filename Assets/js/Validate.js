/**
 * Main Validate JS Class
 *
 * Validate fields
 *
 * @since Cuisine 1.4
 */


    var Validate = {


    	empty: function( _string ){

    		if( _string !== undefined && _string !== null && _string !== '' )
    			return true;

    		return false;

    	},

    	email: function( _string ){

    		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    		return reg.test( _string );

    	},

    	number: function( _string ){

    		if( isNaN( _string ) )
    			return false;

    	},

    	has_number: function( _string ){

    		var req = /\d/;
    		return req.test( _string );

    	},

    	zipcode: function( _string ){

    		if( _string.length > 7 )
    			return false;

    		var reg = /^[1-9][0-9]{3} ?[a-z]{2}$/i;
    		return reg.test( _string );

    	}
    };