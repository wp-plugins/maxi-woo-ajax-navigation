<?php

/**
 * Some functions
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/includes
 */

/**
 * Some functions
 *
 * @since      1.0.0
 * @package    waj
 * @subpackage waj/includes
 * @author     Your Name <email@example.com>
 */
class WAJ_Functions {

    /**
     * return json encoded data with with a frames, to avoid parse errors
     * @since ver 1.0.0
     * 
     * @param string $data data to output
     * 
     * @return string <!--WAJ_START-->json_encode($data)<!--WAJ_END-->
     */
    public static function json_encode($data) {
        return '<!--WAJ_START-->' . json_encode( $data ) . '<!--WAJ_END-->';    
    }
    
    /**
     * die and output json encoded data with with a frames, to avoid parse errors
     * @since ver 1.0.0
     * 
     * @param string $data data to output
     * 
     * @output string <!--WAJ_START-->json_encode($data)<!--WAJ_END-->
     * @return void
     */
    public static function die_json_encode($data) {
        wp_die( '<!--WAJ_START-->' . json_encode( $data ) . '<!--WAJ_END-->' );    
    }
    
    /**
     * Function that will check if value is a valid HEX color.
     * @return mixed
     */
    public static function check_color( $value, $default = '' ) { 

        if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
            return $value;
        }

        return $default;
    }    

}


class WSDS_Empty_Class {
    protected $params;
            
    function __get($name) {
        if ( isset($this->params[$name]) ) {
            return $this->params[$name];
        } else {
            return '';    
        }
        
    }

    function __set($name, $value) {
        $this->params[$name] = $value;
        return true;
    }    
    
}
