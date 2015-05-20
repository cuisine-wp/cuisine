/**
 * Main Media JS Class
 *
 * Makes uploading and other Media stuff a LOT easier.
 *
 * @since Cuisine 1.1
 */

    var Media = new function(){


        /*****************************************************************/
        /** MEDIA FUNCTIONS: *********************************************/
        /*****************************************************************/


        //init the variable for the uploader:
        var file_frame;

        //keep the post id variable for later, if we decide to change it:
        var wp_media_post_id = wp.media.model.settings.post.id;


        var PostExtras = PostExtras;


        /**
        *   Init the uploader ( returns a JSON object with attachment(s) ):
        */
        this.uploader = function( options, callback ){
            

            //if the file modal already exists:
            if( file_frame ){
                
                //just re-open it... don't init it:
                file_frame.open();

                //change the post id if set in the options, though;
                if( options.post_id !== null ){
                    file_frame.uploader.uploader.param( 'post_id', options.post_id );
                }

                return;
            
            }else{

                //the file modal doesn't exist yet, first set the corret post_id if set in the options:
                if( options.post_id !== null ){
                    wp.media.model.settings.post.id = options.post_id;
                }
            }

  
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                
                title: options.title,
                button: { text: options.button },
                multiple: options.multiple

            });
 
            
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                
                var attachments = [];

                if( options.multiple === false ){

                    // We set multiple to false so only get one image from the uploader
                    attachments = file_frame.state().get('selection').first().toJSON();
                    
                }else{
                    
                    var selection = file_frame.state().get('selection');

                    //mutiple is set to true, so we need a multidimensional object:
                    selection.each( function( attachment ) {

                        attachments.push( attachment.toJSON() );

                    });


                }

                // Restore the main post ID
                if( options.post_id !== null ){
                    
                    wp.media.model.settings.post.id = wp_media_post_id;

                }

                //do the callback with the attachments:
                callback( attachments, options );

            });
 
            // Finally, open the modal
            file_frame.open();

        }



        /**
        *   Create the video object:
        */  

        this.create_video_object = function( val, callback ){

            var video = [];
            if(val.substring(0, 12) == 'http://vimeo' || val.substring(0,16) == 'http://www.vimeo' || val.substring(0, 13) == 'https://vimeo' || val.substring(0,17) == 'https://www.vimeo'){
                
                var code = val.split('vimeo.com/');
                code = code[1];
                if(code != ''){
                    //setup the video object:
                    video.id = code;
                    video.url = val;
                    video.vidthumb = JSvars.asseturl+'/images/vimeo.jpg';
                    video.vidtype = 'vimeo';
                    video.typeOf = 'video';
                }
        
        
            }else if(val.substring(0, 12) == 'http://youtu' || val.substring(0,18) == 'http://www.youtube' || val.substring(0, 13 == 'https://youtu') || val.substring(0,19) == 'https://www.youtube'){
             
                var code = val.split('v=');
        
                if(code[1] != null && code[1] != ''){
                    code = code[1].split('&');
                    code = code[0];
        
                    if(code != ''){
            
                        video.id = code;
                        video.url = val;
                        video.vidthumb = 'http://img.youtube.com/vi/'+code+'/0.jpg';
                        video.vidtype = 'youtube';
                        video.typeOf = 'video';
            
                    }

                }else{
        
                    code = val.split('.be/');
                 
                    if( code[1] != null && code[1] != ''){
        
                        code = code[1];
                        video.id = code;
                        video.url = val;
                        video.vidthumb = 'http://img.youtube.com/vi/'+code+'/0.jpg';
                        video.vidtype = 'youtube';
                        video.typeOf = 'video';
        
                    }
                }
            }

            callback( video, val );    

        }



        /**
        *   SANITIZE OPTIONS
        */ 
        this.sanitize_uploader_options = function( obj ){
        
            var options = [];
        

                options.title = 'Uploaden';
                options.button = 'Opslaan';
                options.media_type = 'image';
                options.multiple = false;
                options.post_id = null

            return options;
         
        }

    }
