jQuery( document ).ready( function( $ ){

    if( $('.settings-wrapper.type-tabs').length > 0 ){
        //set first current:
        var _slug = $('.tab.current').data( 'slug' );
        $('#tab-'+_slug ).addClass( 'current' );

        $('.tab-wrapper .tab').on( 'click tap', function(){

            $('.tab').removeClass( 'current' );
            $('.tab-content').removeClass( 'current' );
            $( this ).addClass( 'current' );
            var _slug = $( this ).data( 'slug' );
            $('#tab-'+_slug ).addClass( 'current' );

        });

    }

});