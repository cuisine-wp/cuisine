<?php
namespace Cuisine\Fields;


class MediaField extends DefaultField{

 

    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'media';
    }



    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $media = $this->getValue();

        $html = '<div class="media-grid">';

            $html .= '<div class="media-inner">';

            if( $media ){

                //loop through the media-items:
                foreach( $media as $img ){

                    //render 'em
                    $html .= $this->makeItem( $img );
                }


            }else{

                $html .= '<p class="no-media">Geen media gevonden.</p>';

            }

            $html .= '</div>';

            $html .= $this->makeControls();

        $html .= '</div>';

        return $html;
    }




    /**
     * Get a single media-item
     * 
     * @return String
     */
    public function makeItem( $item ){

        $prefix = $this->name.'['.$item['id'].']';

        $html = '<div class="media-item">';

            $html .= '<div class="img-wrapper">';

                $html .= '<img src="'.$item['preview'].'"/>';

                $html .= '<div class="hover">';

                    $html .= '<div class="edit-btn" data-id="'.$item['img-id'].'"></div>';
                    $html .= '<div class="remove-btn" data-id="'.$item['img-id'].'"></div>';

                $html .= '</div>';

            $html .= '</div>';

            $html .= '<input type="hidden" name="'.$prefix.'[img-id]" value="'.$item['img-id'].'" id="img-id"/>';
            $html .= '<input type="hidden" name="'.$prefix.'[preview]" value='.$item['preview'].' id="preview"/>';
            $html .= '<input type="hidden" name="'.$prefix.'[position]" value="'.$item['position'].'" id="position"/>';

        $html .= '</div>';

        return $html;

    }

    /**
     * Return the template, for Javascript
     * 
     * @return String
     */
    public function renderTemplate(){

        //make a clonable item, for javascript:
        $html = '<script type="text/template" id="media_item_template">';
            $html .= $this->makeItem( array( 
                'id' => '{{item_id}}',
                'preview' => '{{preview_url}}', 
                'img-id' => '{{img_id}}',
                'position' => '{{position}}',
            ) );
        $html .= '</script>';

        return $html;
    }



    /**
     * Show the controls for this field
     * 
     * @return String
     */
    public function makeControls(){

        $html = '<div class="controls">';

            $html .= '<button id="media-add" class="button button-primary">Media toevoegen</button>';

        $html .= '</div>';

        return $html;

    }


}