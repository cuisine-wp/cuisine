<?php

use Cuisine\Wrappers\Field;

class FalseTest extends PHPUnit_Framework_TestCase{

	
    public function testField(){

    	$field = Field::input( 'name', 'Label' );
        $this->assertNotFalse( $field );
    
    }
    
}
?>