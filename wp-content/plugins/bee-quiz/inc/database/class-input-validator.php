<?php

/**
 * @package BeeQuiz
 */

class Quiz_Input_Validator
{
    // TODO: Make the methods of this class static.
    
    const MAX_QUESTION_STRING_LENGTH = 80;
    const MAX_ANSWER_STRING_LENGTH = 40;
    const MAX_SHORT_STRING_LENGTH = 140;
    const MAX_LONG_STRING_LENGTH = 500;
    const MAX_IMAGE_NAME_LENGTH = 80;
    const MAX_ATTRIBUTE_STRING_LENGTH = 32;

    public function validate_question_string( $question_string ) {
        return strlen( $question_string ) <= self::MAX_QUESTION_STRING_LENGTH;
    }

    public function validate_answer_string( $answer_string ) {
        return strlen( $answer_string ) <= self::MAX_ANSWER_STRING_LENGTH;
    }

    public function validate_organisation_short_string( $short_string ) {
        return strlen( $short_string ) <= self::MAX_SHORT_STRING_LENGTH;
    }

    public function validate_organisation_long_string( $long_string ) {
        return strlen( $long_string ) <= self::MAX_LONG_STRING_LENGTH;
    }

    public function validate_organisation_image( $image ) {
        $valid_types = array( 'jpeg', 'jpg', 'png' );

        // Validate error.
        if ( $image[ 'error' ] != 0 ) {
            return false;
        }

        // Validate image name.
        if ( sizeof( $image[ 'name' ] ) > self::MAX_IMAGE_NAME_LENGTH ) {
            return false;
        }

        // Validate image type.
        $is_valid_type = false;
        foreach( $valid_types as $type ) {
            if ( strpos( $image[ 'type' ], $type ) !== false ) {
                $is_valid_type = true;
            }
        }
        if ( ! $is_valid_type ) {
            return false;
        }

        // Validate image size.
        if ( $image[ 'size' ] > (2 * 1024 * 1024) ) {
            return false;
        }

        // All checks have passed.
        return true;
    }

    public static function validate_attribute( $input ) {
        return $input == 0 || $input == 1;
    }

    /**
     * DEPRECATED
     * @param  $attribute  Represents an attribute in the form
     *                     array( <name>, <value> ).
     */
    public static function validate_attribute_depr( $key, $value ) {
        require_once plugin_dir_path( __FILE__ ) . 'class-database-schema.php';

        switch ( Quiz_Database_Schema::ATTRIBUTE_SCHEMA[ $key ] ) {
            case Quiz_Database_Schema::TYPES[ 'd' ]:
                return $value == 0 || $value == 1;
                break;
            case Quiz_Database_Schema::TYPES[ 's' ]:
                return strlen( $value ) <= 32;
                break;
            default:
                // If we encounter this, our schema is incorrect.
                exit;
                break;
        }
    }
}