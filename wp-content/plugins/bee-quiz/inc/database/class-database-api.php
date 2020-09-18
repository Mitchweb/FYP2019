<?php

/**
 * @package BeeQuiz
 */

class Quiz_Database_API
{
    const ORGANISATION_STATE_APPROVED = 0;
    const ORGANISATION_STATE_PENDING = 1;
    const ORGANISATION_STATE_REJECTED = 2;

    private $validator;

    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-schema.php';
        require_once plugin_dir_path( __FILE__ ) . '../database/class-input-sanitizer.php';
        require_once plugin_dir_path( __FILE__ ) . '../database/class-input-validator.php';

        if ( class_exists( 'Quiz_Input_Validator' ) ) {
            $this->validator = new Quiz_Input_Validator();
        }
    }

    public static function create_tables() {
        global $wpdb;

        $charset = $wpdb->charset;
        $collate = $wpdb->collate;

        $sql = "CREATE TABLE bee_quiz_questions (
                    question_id INT NOT NULL AUTO_INCREMENT,
                    question_string VARCHAR(80),
                    question_number TINYINT UNIQUE,
                    PRIMARY KEY (question_id)
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );

        $sql = "CREATE TABLE bee_quiz_answers (
                    answer_id INT NOT NULL AUTO_INCREMENT,
                    answer_string VARCHAR(40),
                    answer_number TINYINT,
                    question_id INT,
                    PRIMARY KEY (answer_id),
                    UNIQUE KEY (answer_number, question_id),
                    FOREIGN KEY (question_id)
                        REFERENCES bee_quiz_questions (question_id)
                        ON DELETE CASCADE
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );

        $sql = "CREATE TABLE bee_quiz_organisations (
                    organisation_id INT NOT NULL AUTO_INCREMENT,
                    organisation_state TINYINT,
                    organisation_name VARCHAR(140) UNIQUE,
                    organisation_description VARCHAR(500),
                    organisation_contact_picture VARCHAR(140),
                    organisation_contact_name VARCHAR(140),
                    organisation_contact_email_address VARCHAR(140),
                    organisation_address_line_1 VARCHAR(140),
                    organisation_address_line_2 VARCHAR(140),
                    organisation_address_line_3 VARCHAR(140),
                    organisation_address_town_city VARCHAR(140),
                    organisation_address_postcode VARCHAR(140),
                    PRIMARY KEY (organisation_id)
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );

        // Prepare columns.
        
        // $columns = '';
        // foreach ( Quiz_Database_Schema::ATTRIBUTE_SCHEMA as $key => $value ) {
        //     $columns .= $key . ' TINYINT(1), ';
        // }

        // $sql = "CREATE TABLE bee_quiz_attributes (
        //             attribute_id INT NOT NULL AUTO_INCREMENT,
        //             organisation_id INT UNIQUE, " . $columns . "
        //             PRIMARY KEY (attribute_id),
        //             FOREIGN KEY (organisation_id)
        //                 REFERENCES bee_quiz_organisations (organisation_id)
        //                 ON DELETE CASCADE
        //         )
        //         CHARACTER SET $charset
        //         COLLATE $collate;";
        // $wpdb->query( $sql );

        $sql = "CREATE TABLE bee_quiz_attributes (
                    attribute_id INT NOT NULL AUTO_INCREMENT,
                    attribute_name VARCHAR(80) NOT NULL UNIQUE,
                    PRIMARY KEY (attribute_id)
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );

        // Get attributes and populate the bee_quiz_attributes table.
        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-schema.php';
        foreach ( Quiz_Database_Schema::ATTRIBUTE_SCHEMA as $key => $value ) {
            $wpdb->query( $wpdb->prepare( 'INSERT INTO bee_quiz_attributes (attribute_name) VALUES (%s)', $key ) );
        }

        $sql = "CREATE TABLE bee_quiz_answer_list (
                    answer_list_id INT NOT NULL AUTO_INCREMENT,
                    answer_id INT,
                    question_id INT,
                    PRIMARY KEY (answer_list_id),
                    UNIQUE KEY (answer_id, question_id),
                    FOREIGN KEY (answer_id)
                        REFERENCES bee_quiz_answers (answer_id)
                        ON DELETE CASCADE,
                    FOREIGN KEY (question_id)
                        REFERENCES bee_quiz_questions (question_id)
                        ON DELETE CASCADE
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );

        $sql = "CREATE TABLE bee_quiz_attribute_relationships (
                    attribute_relationship_id INT NOT NULL AUTO_INCREMENT,
                    attribute_id INT,
                    answer_id INT,
                    organisation_id INT,
                    PRIMARY KEY (attribute_relationship_id),
                    FOREIGN KEY (attribute_id)
                        REFERENCES bee_quiz_attributes (attribute_id)
                        ON DELETE CASCADE,
                    FOREIGN KEY (answer_id)
                        REFERENCES bee_quiz_answers (answer_id)
                        ON DELETE CASCADE,
                    FOREIGN KEY (organisation_id)
                        REFERENCES bee_quiz_organisations (organisation_id)
                        ON DELETE CASCADE
                )
                CHARACTER SET $charset
                COLLATE $collate;";
        $wpdb->query( $sql );
    }

    public static function drop_tables() {
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS bee_quiz_attribute_relationships;";
        $wpdb->query( $sql );

        $sql = "DROP TABLE IF EXISTS bee_quiz_answer_list;";
        $wpdb->query( $sql );

        $sql = "DROP TABLE IF EXISTS bee_quiz_attributes;";
        $wpdb->query( $sql );

        $sql = "DROP TABLE IF EXISTS bee_quiz_organisations;";
        $wpdb->query( $sql );

        $sql = "DROP TABLE IF EXISTS bee_quiz_answers;";
        $wpdb->query( $sql );

        $sql = "DROP TABLE IF EXISTS bee_quiz_questions;";
        $wpdb->query( $sql );
    }

    // MARK :- Getters

    public function get_question( $question_number ) {
        $question_number = intval( $question_number );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT question_string FROM bee_quiz_questions WHERE question_number = %d', $question_number ) );
    }

    public function get_questions() {
        global $wpdb;
        return $wpdb->get_results( 'SELECT question_number FROM bee_quiz_questions' );
    }

    public function get_answer( $answer_number, $question_number ) {
        $answer_number = intval( $answer_number );
        $question_number = intval( $question_number );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT answer_string FROM bee_quiz_answers WHERE answer_number = %d AND question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d)', $answer_number, $question_number ) );
    }

    public function get_answer_id( $answer_number, $question_number ) {
        $answer_number = intval( $answer_number );
        $question_number = intval( $question_number );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT answer_id FROM bee_quiz_answers WHERE answer_number = %d AND question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d)', $answer_number, $question_number ) );
    }

    public function get_answer_ids( $question_number = 0 ) {
        global $wpdb;

        if ( $question_number != 0 ) {
            return $wpdb->get_results( $wpdb->prepare( 'SELECT answer_id FROM bee_quiz_answer_list WHERE question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d) ORDER BY answer_id ASC', $question_number ) );
        } else {
            return $wpdb->get_results( 'SELECT answer_id FROM bee_quiz_answers ORDER BY answer_id ASC' );
        }
    }

    public function get_organisation_details( $organisation_id ) {
        $organisation_id = intval( $organisation_id );

        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( 'SELECT organisation_name, organisation_description, organisation_contact_picture, organisation_contact_name, organisation_contact_email_address FROM bee_quiz_organisations WHERE organisation_id = %d', $organisation_id ) );
    }

    public function get_organisations() {
        global $wpdb;
        return $wpdb->get_results( 'SELECT organisation_id, organisation_name FROM bee_quiz_organisations' );
    }

    public function get_organisation_names() {
        global $wpdb;
        return $wpdb->get_results( $wpdb->prepare( 'SELECT organisation_name FROM bee_quiz_organisations WHERE organisation_state = %d', 1 ) );
    }

    public function get_first_organisation() {
        global $wpdb;
        return $wpdb->get_var( 'SELECT organisation_id FROM bee_quiz_organisations' );
    }

    public function get_attribute_answer( $attribute_id, $answer_id ) {
        $attribute_id = intval( $attribute_id );
        $answer_id = intval( $answer_id );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT attribute_relationship_id FROM bee_quiz_attribute_relationships WHERE attribute_id = %d AND answer_id = %d', $attribute_id, $answer_id ) );
    }

    public function get_attributes_organisation( $organisation_id ) {
        $organisation_id = intval( $organisation_id );

        global $wpdb;
        return $wpdb->get_col( $wpdb->prepare( 'SELECT attribute_id FROM bee_quiz_attribute_relationships WHERE organisation_id = %d', $organisation_id ) );
    }

    public function get_attribute( $organisation_id, $key ) {
        $organisation_id = intval( $organisation_id );

        if ( ! Quiz_Database_Schema::check_attribute( $key ) ) {
            // We should never fail.
        }

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT ' . $key . ' FROM bee_quiz_attributes WHERE organisation_id = %d', $organisation_id ) );
    }

    public function get_attribute_id( $attribute_name ) {
        $attribute_name = sanitize_text_field( $attribute_name );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT attribute_id FROM bee_quiz_attributes WHERE attribute_name = %s', $attribute_name ) );
    }

    public function get_attributes() {
        global $wpdb;
        return $wpdb->get_results( 'SELECT attribute_id, attribute_name FROM bee_quiz_attributes ORDER BY attribute_id ASC' );
    }

    public function get_count_questions() {
        global $wpdb;
        return $wpdb->get_var( 'SELECT COUNT(question_id) FROM bee_quiz_questions' );
    }

    public function get_count_answers( $question_number ) {
        $question_number = intval( $question_number );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(answer_id) FROM bee_quiz_answers WHERE question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d)', $question_number ) );
    }

    public function get_max_question_number() {
        global $wpdb;
        return $wpdb->get_var( 'SELECT MAX(question_number) FROM bee_quiz_questions' );
    }

    // MARK :- Getters (Frontend)

    public function get_first_quiz_question() {
        global $wpdb;
        return $wpdb->get_var( 'SELECT bee_quiz_questions.question_id FROM bee_quiz_questions LEFT JOIN bee_quiz_answer_list ON bee_quiz_questions.question_id = bee_quiz_answer_list.question_id WHERE bee_quiz_answer_list.question_id IS NULL' );
    }

    public function get_quiz_question( $question_id ) {
        $question_id = intval( $question_id );

        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( 'SELECT question_string FROM bee_quiz_questions WHERE question_id = %d', $question_id ) );
    }

    public function get_quiz_answers( $question_id ) {
        $question_id = intval( $question_id );

        global $wpdb;
        return $wpdb->get_results( $wpdb->prepare( 'SELECT answer_id, answer_string FROM bee_quiz_answers WHERE question_id = %d ORDER BY answer_id ASC', $question_id ) );
    }

    public function get_next_quiz_question( $answer_id ) {
        $answer_id = intval( $answer_id );

        global $wpdb;
        return $wpdb->get_col( $wpdb->prepare( 'SELECT question_id FROM bee_quiz_answer_list WHERE answer_id = %d', $answer_id ) );
    }

    // MARK :- Setters

    public function replace_question( $question_string, $question_number ) {
        $question_string = sanitize_text_field( stripslashes( $question_string ) );
        $question_number = intval( $question_number );

        if ( ! $this->validator->validate_question_string( $question_string ) ) {
            // Invalid input. Throw an error.
            return false;
        }

        global $wpdb;

        $var = $wpdb->get_var( $wpdb->prepare( 'SELECT question_string FROM bee_quiz_questions WHERE question_string = %s AND question_number = %d', $question_string, $question_number ) );
        if ( ! is_null( $var ) ) {
            // We've found a duplicate entry, so silently move on.
            return true;
        }

        $wpdb->replace(
            'bee_quiz_questions',
            array(
                'question_string' => $question_string,
                'question_number' => $question_number
            ),
            array(
                '%s',
                '%d'
            )
        );
        
        return true;
    }

    public function delete_question( $question_string, $question_number ) {
        $question_string = sanitize_text_field( stripslashes( $question_string ) );
        $question_number = intval( $question_number );

        if ( ! $this->validator->validate_question_string( $question_string ) ) {
            // Invalid input. Throw an error.
            return false;
        }

        global $wpdb;

        $var = $wpdb->delete(
            'bee_quiz_questions',
            array(
                'question_string' => $question_string,
                'question_number' => $question_number
            ),
            array(
                '%s',
                '%d'
            )
        );
        
        return true;
    }

    public function replace_answer( $answer_string, $answer_number, $question_number ) {
        $answer_string = sanitize_text_field( stripslashes( $answer_string ) );
        $answer_number = intval( $answer_number );
        $question_number = intval( $question_number );

        if ( ! $this->validator->validate_answer_string( $answer_string ) ) {
            // Invalid input. Throw an error.
            return false;
        }

        global $wpdb;

        $var = $wpdb->get_var( $wpdb->prepare( 'SELECT answer_string FROM bee_quiz_answers WHERE answer_string = %s AND answer_number = %d AND question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d)', $answer_string, $answer_number, $question_number ) );
        if ( ! is_null( $var ) ) {
            // We've found a duplicate entry, so silently move on.
            return true;
        }

        $wpdb->replace(
            'bee_quiz_answers',
            array(
                'answer_string' => $answer_string,
                'answer_number' => $answer_number,
                'question_id' => $wpdb->get_var( $wpdb->prepare( 'SELECT question_id FROM bee_quiz_questions WHERE question_number = %d', $question_number ) )
            ),
            array(
                '%s',
                '%d',
                '%d'
            )
        );

        return true;
    }

    public function replace_answer_list( $answer_list, $question_number ) {
        $question_number = intval( $question_number );

        global $wpdb;

        $this->delete_answer_list( $question_number );

        foreach ( $answer_list as $key => $answer_id ) {
            $answer_id = intval( $answer_id );

            $var = $wpdb->get_var( $wpdb->prepare( 'SELECT answer_list_id FROM bee_quiz_answer_list WHERE answer_id = %d AND question_id = (SELECT question_id FROM bee_quiz_questions WHERE question_number = %d)', $answer_id, $question_number ) );
            if ( ! is_null( $var ) ) {
                // We've found a duplicate entry, so silently move on.
                return true;
            }

            $wpdb->replace(
                'bee_quiz_answer_list',
                array(
                    'answer_id' => $answer_id,
                    'question_id' => $wpdb->get_var( $wpdb->prepare( 'SELECT question_id FROM bee_quiz_questions WHERE question_number = %d', $question_number ) )
                ),
                array(
                    '%d',
                    '%d'
                )
            );
        }

        return true;
    }

    public function delete_answer_list( $question_number ) {
        $question_number = intval( $question_number );

        global $wpdb;

        $wpdb->delete(
            'bee_quiz_answer_list',
            array(
                'question_id' => $wpdb->get_var( $wpdb->prepare( 'SELECT question_id FROM bee_quiz_questions WHERE question_number = %d', $question_number ) )
            ),
            array(
                '%d'
            )
        );
    }

    public function replace_organisation( $name, $description, $contact_picture, $contact_name, $contact_email_address, $address_line_1, $address_line_2, $address_line_3, $address_town_city, $address_postcode ) {
        $name = sanitize_text_field( stripslashes( $name ) );
        $description = sanitize_text_field( stripslashes( $description ) );
        // $contact_picture is $_FILES, so does not require text field sanitisation.
        $contact_name = sanitize_text_field( stripslashes( $contact_name ) );
        $contact_email_address = sanitize_text_field( stripslashes( $contact_email_address ) );
        $address_line_1 = sanitize_text_field( stripslashes( $address_line_1 ) );
        $address_line_2 = sanitize_text_field( stripslashes( $address_line_2 ) );
        $address_line_3 = sanitize_text_field( stripslashes( $address_line_3 ) );
        $address_town_city = sanitize_text_field( stripslashes( $address_town_city ) );
        $address_postcode = sanitize_text_field( stripslashes( $address_postcode ) );

        if ( ! $this->validator->validate_organisation_short_string( $name ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_long_string( $description ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_image( $contact_picture ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $contact_name ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $contact_email_address ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $address_line_1 ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $address_line_2 ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $address_line_3 ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $address_town_city ) ) {
            return false;
        }
        if ( ! $this->validator->validate_organisation_short_string( $address_postcode ) ) {
            return false;
        }

        global $wpdb;

        $upload = wp_handle_upload( $contact_picture, array( 'action' => 'submit_registration_form' ) ); // Upload the contact picture to WordPress.

        $var = $wpdb->get_var( $wpdb->prepare( 'SELECT organisation_name FROM bee_quiz_organisations WHERE organisation_name = %s', $name ) );
        if ( ! is_null( $var ) ) {
            // We've found a duplicate entry, so silently move on.
            return true;
        }

        $wpdb->replace(
            'bee_quiz_organisations',
            array(
                'organisation_state' => self::ORGANISATION_STATE_PENDING,
                'organisation_name' => $name,
                'organisation_description' => $description,
                'organisation_contact_picture' => $upload[ 'url' ],
                'organisation_contact_name' => $contact_name,
                'organisation_contact_email_address' => $contact_email_address,
                'organisation_address_line_1' => $address_line_1,
                'organisation_address_line_2' => $address_line_2,
                'organisation_address_line_3' => $address_line_3,
                'organisation_address_town_city' => $address_town_city,
                'organisation_address_postcode' => $address_postcode
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        return true;
    }

    public function replace_attribute_answer( $attribute_id, $answer_id ) {
        $attribute_id = intval( $attribute_id );
        $answer_id = intval( $answer_id );

        global $wpdb;

        $var = $wpdb->get_var( $wpdb->prepare( 'SELECT attribute_relationship_id FROM bee_quiz_attribute_relationships WHERE attribute_id = %d AND answer_id = %d', $attribute_id, $answer_id ) );
        if ( ! is_null( $var ) ) {
            // We've found a duplicate entry, so silently move on.
            return true;
        }

        $wpdb->replace(
            'bee_quiz_attribute_relationships',
            array(
                'attribute_id' => $attribute_id,
                'answer_id' => $answer_id
            ),
            array(
                '%d',
                '%d'
            )
        );

        return true;
    }

    public function delete_attribute_answer( $answer_id ) {
        $answer_id = intval( $answer_id );

        global $wpdb;

        $wpdb->delete(
            'bee_quiz_attribute_relationships',
            array(
                'answer_id' => $answer_id
            ),
            array(
                '%d'
            )
        );
    }

    public function replace_attributes_organisation( $organisation_id, $attributes ) {
        $organisation_id = intval( $organisation_id );

        global $wpdb;

        $this->delete_attributes_organisation( $organisation_id );

        // Stuff.
        foreach ( $attributes as $attribute_id ) {
            $attribute_id = intval( $attribute_id );

            $wpdb->insert(
                'bee_quiz_attribute_relationships',
                array(
                    'attribute_id' => $attribute_id,
                    'organisation_id' => $organisation_id
                ),
                array(
                    '%d',
                    '%d'
                )
            );
        }

        return true;
    }

    public function delete_attributes_organisation( $organisation_id ) {
        $organisation_id = intval( $organisation_id );

        global $wpdb;
        $wpdb->delete(
            'bee_quiz_attribute_relationships',
            array(
                'organisation_id' => $organisation_id
            ),
            array(
                '%d'
            )
        );
    }

    /** DEPRECATED */
    public function replace_attributes( $organisation_id, $attributes ) {
        foreach ( $attributes as $key => $input ) {
            if ( ! Quiz_Database_Schema::check_attribute( $key ) ) {
                // At least one attribute isn't part of the schema.
                return false;
            }
        }

        // Sanitisation.
        foreach ( $attributes as $key => &$input ) {
            Quiz_Input_Sanitizer::sanitize_attribute( $input );
        }
        unset( $input ); // Unset reference.
        
        // Validation.
        foreach ( $attributes as $key => $input ) {
            if ( ! Quiz_Input_Validator::validate_attribute( $input ) ) {
                // At least one attribute is invalid.
                return false;
            }
        }

        // Prepare attributes array for insertion.
        $attributes[ 'organisation_id' ] = intval( $organisation_id );

        // Prepare types array for insertion.
        $types = array();
        $i = 0;
        foreach ( $attributes as $attribute ) {
            $types[ $i ] = '%d';
        }

        // Check that the attributes and types arrays are the same length.
        if ( ! sizeof( $attributes ) == sizeof( $types ) ) {
            return false;
        }

        global $wpdb;

        $wpdb->replace(
            'bee_quiz_attributes',
            $attributes,
            $types
        );

        return true;
    }
}

