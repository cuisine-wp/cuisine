<?php
namespace Cuisine\Utilities;

class Number {


	/**
	 * Return the number as a currency 
	 * 
	 * @param  int|float $number
	 * @return string
	 */
	public static function currency( $number ){
        
        return number_format( (float) $number, 2, ',', '.' );

	}


    /**
     * Returns the percentage from param1 to param 2
     * 
     * @param  int|float  $number
     * @param  int|float $compare
     * @return int
     */
    public static function toPercent( $number, $compare = 100 ){

        return ( $number / $compare ) * 100;

    }


    /**
     * Return if this number is higher than 1
     * 
     * @param  int|float  $number
     * @return boolean
     */
    public static function isPlural( $number ){

        if( $number > 1 )
            return true;

        return false;

    }



	/**
     * Converts a number into the text equivalent. For example, 456 becomes four
     * hundred and fifty-six.
     *
     * Part of the IntToWords Project.
     *
     * @author Brandon Wamboldt
     * @see https://github.com/brandonwamboldt/utilphp/
     *
     * @param  int|float $number The number to convert into text
     * @return string
     */
    public static function toWord( $number ){ 

        $number = (string) $number;

        if (strpos($number, '.') !== false) {
            list($number, $decimal) = explode('.', $number);
        } else {
            $decimal = false;
        }

        $output = '';

        if ($number[0] == '-') {
            $output = 'negative ';
            $number = ltrim($number, '-');
        } elseif ($number[0] == '+') {
            $output = 'positive ';
            $number = ltrim($number, '+');
        }

        if ($number[0] == '0') {
            $output .= 'zero';
        } else {
            $length = 19;
            $number = str_pad($number, 60, '0', STR_PAD_LEFT);
            $group  = rtrim(chunk_split($number, 3, ' '), ' ');
            $groups = explode(' ', $group);
            $groups2 = array();

            foreach ($groups as $group) {
                $group[1] = isset($group[1]) ? $group[1] : null;
                $group[2] = isset($group[2]) ? $group[2] : null;
                $groups2[] = self::toWordThreeDigits($group[0], $group[1], $group[2]);
            }

            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != '') {
                    $output .= $groups2[$z] . self::toWordConvertGroup($length - $z);
                    $output .= ($z < $length && ! array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[$length] != '' && $groups[$length][0] == '0' ? ' and ' : ', ');
                }
            }

            $output = rtrim($output, ', ');
        }

        if ($decimal > 0) {
            $output .= ' point';

            for ($i = 0; $i < strlen($decimal); $i++) {
                $output .= ' ' . self::toWordConvertDigit($decimal[$i]);
            }
        }

        return $output;
    }

    /**
     * Convert a group of numbers
     * 
     * @author Brandon Wamboldt
     * @see https://github.com/brandonwamboldt/utilphp/
     */
    protected static function toWordConvertGroup( $index ){

        switch( $index ) {
            case 11:
                return ' decillion';
            case 10:
                return ' nonillion';
            case 9:
                return ' octillion';
            case 8:
                return ' septillion';
            case 7:
                return ' sextillion';
            case 6:
                return ' quintrillion';
            case 5:
                return ' quadrillion';
            case 4:
                return ' trillion';
            case 3:
                return ' billion';
            case 2:
                return ' million';
            case 1:
                return ' thousand';
            case 0:
                return '';
        }

        return '';
    }


    /**
     * Convert a group of three digits
     * 
     * @author Brandon Wamboldt
     * @see https://github.com/brandonwamboldt/utilphp/
     */
    protected static function toWordThreeDigits( $digit1, $digit2, $digit3 ){
        $output = '';

        if ($digit1 == '0' && $digit2 == '0' && $digit3 == '0') {
            return '';
        }

        if ($digit1 != '0') {
            $output .= self::toWordConvertDigit($digit1) . ' hundred';

            if ($digit2 != '0' || $digit3 != '0') {
                $output .= ' and ';
            }
        }
        if ($digit2 != '0') {
            $output .= self::toWordTwoDigits($digit2, $digit3);
        } elseif ($digit3 != '0') {
            $output .= self::toWordConvertDigit($digit3);
        }

        return $output;
    }


    /**
     * Convert a group of two digits
     * 
     * @author Brandon Wamboldt
     * @see https://github.com/brandonwamboldt/utilphp/
     */
    protected static function toWordTwoDigits( $digit1, $digit2 ){

        if ($digit2 == '0') {
        
            switch ($digit1) {
                case '1':
                    return 'ten';
                case '2':
                    return 'twenty';
                case '3':
                    return 'thirty';
                case '4':
                    return 'forty';
                case '5':
                    return 'fifty';
                case '6':
                    return 'sixty';
                case '7':
                    return 'seventy';
                case '8':
                    return 'eighty';
                case '9':
                    return 'ninety';
            }
            
        } elseif ($digit1 == '1') {
            switch ($digit2) {
                case '1':
                    return 'eleven';
                case '2':
                    return 'twelve';
                case '3':
                    return 'thirteen';
                case '4':
                    return 'fourteen';
                case '5':
                    return 'fifteen';
                case '6':
                    return 'sixteen';
                case '7':
                    return 'seventeen';
                case '8':
                    return 'eighteen';
                case '9':
                    return 'nineteen';
            }
        } else {
            $second_digit = self::toWordConvertDigit($digit2);

            switch ($digit1) {
                case '2':
                    return "twenty-{$second_digit}";
                case '3':
                    return "thirty-{$second_digit}";
                case '4':
                    return "forty-{$second_digit}";
                case '5':
                    return "fifty-{$second_digit}";
                case '6':
                    return "sixty-{$second_digit}";
                case '7':
                    return "seventy-{$second_digit}";
                case '8':
                    return "eighty-{$second_digit}";
                case '9':
                    return "ninety-{$second_digit}";
            }
        }
    }

    /**
     * Convert a single number
     * 
     * @author Brandon Wamboldt
     * @see https://github.com/brandonwamboldt/utilphp/
     * 
     * @param $digit
     * @return string
     * @throws \LogicException
     */
    protected static function toWordConvertDigit( $digit ){

        switch ( $digit ) {
            case '0':
                return 'zero';
            case '1':
                return 'one';
            case '2':
                return 'two';
            case '3':
                return 'three';
            case '4':
                return 'four';
            case '5':
                return 'five';
            case '6':
                return 'six';
            case '7':
                return 'seven';
            case '8':
                return 'eight';
            case '9':
                return 'nine';
            default:
                throw new \LogicException('Not a number');
        }
    }


}
