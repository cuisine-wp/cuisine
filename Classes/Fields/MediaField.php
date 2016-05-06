<?php
namespace Cuisine\Fields;


use Cuisine\Utilities\Sort;

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
        $datas = $this->getDatas();
        
        $html = '<div class="media-grid" '.$datas.'>';

            $html .= '<div class="media-inner">';
            
            if( $media ){

                $media = Sort::byField( $media, 'position', 'ASC' );

                //loop through the media-items:
                foreach( $media as $key => $img ){

                    //render 'em
                    $img['id'] = $key;
                    $html .= $this->makeItem( $img );
                }
            }

            $html .= '</div>';
            $html .= '<div class="clearfix"></div>';


            $html .= '<p class="no-media">Geen media gevonden.</p>';


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

        $html = '<div class="media-item ui-state-default">';

            $html .= '<div class="img-wrapper">';

                $html .= '<img src="'.$item['preview'].'"/>';

            $html .= '</div>';

            $html .= '<div class="hover">';

            // $html .= '<div class="edit-btn" data-id="'.$item['img-id'].'"></div>';
                $html .= '<div class="remove-btn" data-id="'.$item['img-id'].'">';
                $html .= '<span class="dashicons dashicons-trash"></span></div>';

            $html .= '</div>';


            $html .= '<input type="hidden" class="multi" name="'.$prefix.'[img-id]" value="'.$item['img-id'].'" id="img-id"/>';
            $html .= '<input type="hidden" class="multi" name="'.$prefix.'[preview]" value='.$item['preview'].' id="preview"/>';
            $html .= '<input type="hidden" class="multi" name="'.$prefix.'[position]" value="'.$item['position'].'" id="position"/>';


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
        $html = '<script type="text/template" id="'.$this->name.'_item_template">';
            $html .= $this->makeItem( array( 
                'id' => '<%= item_id %>',
                'preview' => '<%= preview_url %>', 
                'img-id' => '<%= img_id %>',
                'position' => '<%= position %>',
            ) );
        $html .= '</script>';

        return $html;
    }



    private function getDatas(){

        $datas = '';
        $datas .= 'data-id="'.$this->id.'" ';
        $datas .= 'data-name="'.$this->name.'" ';
        $datas .= 'data-highest-id="'.$this->getHighestItemId().'" ';

        return $datas;
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

    /**
     * Get the highest item ID available
     * 
     * @return int
     */
    private function getHighestItemId(){

        $media = $this->getValue();
        $id = 0;

        if( !empty( $media ) ){
            foreach( $media as $key => $val ){
    
                if( $key > $id )
                    $id = $key;
    
            }
        }

        return $id + 1;

    }


}