<?php
    
class phpGraph_Color {
    static private $_colors = array();
    
    static private $_background = null;
    
    /**
     * To generate hexadecimal code for color
     * @param null
     * @return string hexadecimal code
     *
     * @author Cyril MAGUIRE
     */
    static public function genColor() {
        $rgbArray = phpGraph_Color::hslToRgb(rand(0, 255), rand(80, 100), rand(50, 70));
        $hexa = '';
        foreach ($rgbArray as $val) {
            $hexa .= sprintf("%02x", $val);
        }
        if ('#' . $hexa == self::$_background) {
            return self::genColor();
        }
        if (!in_array($hexa, self::$_colors)) {
            self::$_colors[] = $hexa;
            return '#' . $hexa;
        } else {
            return self::genColor();
        }
    }
    
    static function setBackgroundColor($background) {
        self::$_background = $background;
    }
    
    static function setColors($colors) {
        self::$_colors = (is_array($colors)) ? $colors : array($colors);
    }
    
    static function rgbToHsl( $r, $g, $b ) {
        $r /= 255;
        $g /= 255;
        $b /= 255;
            
        $max = max( $r, $g, $b );
        $min = min( $r, $g, $b );
            
        $h = 0;
        $s = 0;
        $l = ( $max + $min ) / 2;
        $d = $max - $min;
            
            if( $d == 0 ){
                $h = $s = 0; // achromatic
            } else {
                $s = $d / ( 1 - abs( 2 * $l - 1 ) );
                    
            switch( $max ){
                    case $r:
                        $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 ); 
                            if ($b > $g) {
                            $h += 360;
                        }
                        break;
                            
                    case $g: 
                        $h = 60 * ( ( $b - $r ) / $d + 2 ); 
                        break;
                            
                    case $b: 
                        $h = 60 * ( ( $r - $g ) / $d + 4 ); 
                        break;
                }                                
        }
            
        return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
    }
        
    static function hslToRgb( $h, $s, $l ) {

        $h /= 360;
        $s /= 100;
        $l /= 100;

        $m2 = ($l <= 0.5) ? $l * ($s + 1) : $l + $s - $l * $s;
        $m1 = 2 * $l - $m2;

        $r = round(255.0 * self::_h_to_rgb($h, $m1, $m2)); 
        $g = round(255.0 * self::_h_to_rgb($h + 1/3, $m1, $m2)); 
        $b = round(255.0 * self::_h_to_rgb($h - 1/3, $m1, $m2)); 

        return array( floor( $r ), floor( $g ), floor( $b ) );
    }
    
    static private function _h_to_rgb($h, $m1, $m2){
        $h = $h - floor($h);
        if (6 * $h < 1) {
            $ret = $m1 + 6 * $h * ($m2 - $m1);
        } elseif (2 * $h < 1) {
            $ret = $m2;
        } elseif (3 * $h < 2) {
            $ret = $m1 + 6 * (2 / 3 - $h) * ($m2 - $m1);
        } else {
            $ret = $m1;
        }
        return $ret;
    }
    
}