<?php
namespace Cuisine\Utilities;

class Sort{


	/**
	 * Sort an array by a subfield
	 * 
	 * @param  array $data     array
	 * @param  string $field   name
	 * @param  string $order   ASC / DESC
	 * @return array
	 */
	public static function byField( $data, $field, $order = null ){
	
		if( $order == null || $order == 'ASC' ){
	  		$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	  	}else if( $order == 'DESC' ){
	  		$code = "return strnatcmp(\$b['$field'], \$a['$field']);";
		}
	
		uasort( $data, create_function( '$a,$b', $code ) );
		return $data;
	}


	/**
	 * Get the first item in an array
	 * 
	 * @param  array $array
	 * @return mixed
	 */
	public static function first( array $array ){
		return reset( $array );

	}

	/**
	 * Returns the first key in an array.
	 *
	 * @param  array $array
	 * @return int|string
	 */
	public static function firstKey( array $array ){
		reset( $array );
		return key( $array );
	
	}

	/**
	 * Returns the last element in an array.
	 *
	 * @param  array $array
	 * @return mixed
	 */
	public static function last( array $array ){
	    return end($array);

	}

	/**
	 * Returns the last key in an array.
	 *
	 * @param  array $array
	 * @return int|string
	 */
	public static function lastKey( array $array ){
	    end($array);
	    return key($array);
	
	}

	/**
	 * Accepts an array, and returns an array of values from that array as
	 * specified by $field. For example, if the array is full of objects
	 * and you call util::array_pluck($array, 'name'), the function will
	 * return an array of values from $array[]->name.
	 *
	 * @param  array   $array            An array
	 * @param  string  $field            The field to get values from
	 * @param  boolean $preserve_keys    Whether or not to preserve the
	 *                                   array keys
	 * @param  boolean $remove_nomatches If the field doesn't appear to be set,
	 *                                   remove it from the array
	 * @return array
	 */
	public static function pluck( array $array, $field, $preserve_keys = true, $remove_nomatches = true ){
	    $new_list = array();

	    foreach ($array as $key => $value) {
	        if (is_object($value)) {
	            if (isset($value->{$field})) {
	                if ($preserve_keys) {
	                    $new_list[$key] = $value->{$field};
	                } else {
	                    $new_list[] = $value->{$field};
	                }
	            } elseif (!$remove_nomatches) {
	                $new_list[$key] = $value;
	            }
	        } else {
	            if (isset($value[$field])) {
	                if ($preserve_keys) {
	                    $new_list[$key] = $value[$field];
	                } else {
	                    $new_list[] = $value[$field];
	                }
	            } elseif (!$remove_nomatches) {
	                $new_list[$key] = $value;
	            }
	        }
	    }

	    return $new_list;
	}
}