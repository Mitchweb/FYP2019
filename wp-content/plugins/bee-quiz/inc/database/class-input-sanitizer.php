<?php

/**
 * @package BeeQuiz
 */

class Quiz_Input_Sanitizer
{
    public static function sanitize_string( &$string ) {
        return sanitize_text_field( $string );
    }

    public static function sanitize_int( &$int ) {
        return intval( $int );
    }

    public static function sanitize_attribute( &$input ) {
        $input = intval( $input );
    }

    /**
     * DEPRECATED
     * @param  $value  Represents an attribute in the form
     *                 array( <name>, <value> ).
     */
    public static function sanitize_attribute_depr( $key, &$value ) {
        require_once plugin_dir_path( __FILE__ ) . 'class-database-schema.php';

        switch ( Quiz_Database_Schema::ATTRIBUTE_SCHEMA[ $key ] ) {
            case Quiz_Database_Schema::TYPES[ 'd' ]:
                $value = intval( $value );
                break;
            case Quiz_Database_Schema::TYPES[ 's' ]:
                $value = sanitize_text_field( $value );
                break;
            default:
                // If we encounter this, our schema is incorrect.
                exit;
                break;
        }
    }
}