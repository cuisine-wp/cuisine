<?php
namespace Cuisine\Fields;

use Cuisine\Utilities\Url;

class FileField extends DefaultField{


    /**
     * Method to override to define the input type
     * that handles the value.
     *
     * @return void
     */
    protected function fieldType(){
        $this->type = 'file';

    }


    /**
     * Build the html
     *
     * @return String;
     */
    public function build(){

        $file = $this->getValue();
        $org_file = $file;


        //set defaults
        $file = $this->sanatizeValue( $file );

        $pre = $this->name;

        $html = '<div class="file-field">';

        	$html .= '<div class="file-wrapper">';
                $html .= '<img src="'.$file['icon'].'" id="preview"/><br />';
                $html .= $file['title'];
        	$html .= '</div>';

        	$html .= '<div class="btn-wrapper">';

        		$btnText = ( $org_file ? __( 'Bestand aanpassen', 'cuisine' ) : __( 'Selecteer een bestand', 'cuisine' ) );

                
        		$html .= '<button id="select-file">'.$btnText.'</button>';
                $html .= '<span class="remove-file-source"><em>'.__( 'of', 'cuisine' ).'</em> <span id="remove-file">'.__( 'verwijderen', 'cuisine' ).'</span></span>';

        	$html .= '</div>';

        	$html .= '<input type="hidden" class="multi" name="'.$pre.'[file-id]" id="file-id" value="'.$file['file-id'].'"/>';
        	$html .= '<input type="hidden" class="multi" name="'.$pre.'[title]" id="title" value="'.$file['title'].'"/>';
        	$html .= '<input type="hidden" class="multi" name="'.$pre.'[url]" id="url" value="'.$file['url'].'"/>';
            $html .= '<input type="hidden" class="multi" name="'.$pre.'[mime-type]" id="mime-type" value="'.$file['mime-type'].'"/>';
            $html .= '<input type="hidden" class="multi" name="'.$pre.'[icon]" id="icon" value="'.$file['icon'].'"/>';

        $html .= '</div>';

        return $html;

    }

    function sanatizeValue( $original ){

    	$defaults = array( 
    		'file-id' => '',
    		'title' => '',
    		'url' => '',
            'mime-type' => '',
            'icon' => ''
    	);

        if( !is_array( $original ) )
            $original = array();


    	return array_merge( $defaults, $original );
    }

}