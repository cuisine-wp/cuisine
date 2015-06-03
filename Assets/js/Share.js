define([
    
    'jquery'

], function( $ ){

    $( document ).ready( function(){

        $('.post-counter').on( 'click', function( e ){

            e.preventDefault;

            if( ! $(this).hasClass('post-comments') ){


                var type = $(this).data('type');
                var count = parseInt( $(this).data('count') );
                var pid = $(this).data('postid');
                var obj = $(this);
                
                var data = {
                    action: 'socialShare',
                    post_id: pid,
                    type: type,
                };
                
                window.open( obj.data('href'), '_blank', 'width=626,height=300' );

                //post with ajax:
                $.post( Cuisine.ajax, data, function(response) {

                    if(response != 0 && response != ''){
                        obj.find('p').html( count + 1 );
                    }

                });

            }
        });


    });

});