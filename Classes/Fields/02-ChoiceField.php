<?php
namespace Cuisine\Fields;


class ChoiceField extends DefaultField{

	

	/**
	 * Return html for a single choice
	 * 
	 * @param  array/string $choice
	 * @return string HTML
	 */
	public function buildChoice( $choice ){

	    $html = '';

	    //set choice variables:
	    $id = 'subfield-'.$this->id.'-'.$choice['id'];
	    $name = $choice['key'];
	    $label = ( isset( $choice['label'] ) ? $choice['label'] : false );
	    $selected = $this->getSelectedType();

	    $html = '<label for="'.$id.'">';

	        $html .= '<input type="'.$this->type.'" ';

	        $html .= 'id="'.$id.'" ';

	        $html .= 'class="'.$this->getSubClass().'" ';

	        $html .= 'name="'.$name.'" ';

	        $html .= $this->getDefault();

	        $html .= $this->getValidation();

	        $html .= ( $this->properties['defaultValue'] == $name ? ' '.$selected : '' );

	        $html .= '>';

	        $html .= ( $label ? $label : '' );


	    $html .= '</label>';

	    return $html;

	}



	/**
	 * Get choices
	 *
	 * @return Array / void
	 */
	public function getChoices(){

	    if( $this->properties['options'] )
	        return $this->properties['options'];

	}

	/**
	 * Makes the choices array complete
	 * 
	 * @param  Array $inputs  all default choices
	 * @return Array
	 */
	public function parseChoices( $inputs ){

	    $i = 0;
	    $choices = array();

	    //check to see if it's an associative array
	    $isIndexed = ( array_values( $inputs ) === $inputs );

	    foreach( $inputs as $key => $input ){

	        $choice = array();

	        $choice['id'] = $i;
	        $choice['key'] = ( $isIndexed ? $input : $key );
	        $choice['label'] = $input;
	      
	        $choices[] = $choice;

	        $i++;
	    }

	    return $choices;

	}

}