<?php

/**
 * @package BeeQuiz
 */

class Quiz_Admin_Forms extends Quiz_Properties
{
    public function init() {
        add_action( 'admin_post_quiz_save_settings_form', array( $this, 'save_settings' ) );
        add_action( 'admin_post_quiz_save_attributes_form', array( $this, 'save_attributes' ) );
        add_action( 'admin_post_quiz_save_attributes_answers_form', array( $this, 'save_attributes_answers' ) );
    }

    function save_settings() {
        if ( isset( $_POST[ '_quiz_nonce' ] ) && wp_verify_nonce( $_POST[ '_quiz_nonce' ], 'quiz_save_settings_form' ) && check_admin_referer( 'quiz_save_settings_form', '_quiz_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            // Process Questions.
            $i = 1;
            while ( true ) {
                if ( ! isset( $_POST[ 'quiz_Q' . $i . '_title' ] ) ) {
                    // If we have ran out of questions, exit the loop.
                    break;
                }

                $question_string = $_POST[ 'quiz_Q' . $i . '_title' ];
                $question_number = $i;

                if ( ! $api->replace_question( $question_string, $question_number ) ) {
                    $this->database_error_redirect( 'quiz' );
                }

                // Process Answers.
                $j = 1;
                while ( true ) {
                    if ( ! isset( $_POST[ 'quiz_Q' . $i . '_A' . $j ] ) ) {
                        // If we have ran out of answers, exit the loop.
                        break;
                    }

                    $answer_string = $_POST[ 'quiz_Q' . $i . '_A' . $j ];
                    $answer_number = $j;

                    if ( ! $api->replace_answer( $answer_string, $answer_number, $question_number ) ) {
                        $this->database_error_redirect( 'quiz' );
                    }

                    $j++;
                }

                $i++;
            }

            // Inform the user of the successful form submission.
            $this->save_settings_redirect( 'quiz' );
            exit;
        } else {
            // Invalid submission.
            wp_die( __( 'Invalid submission.', $this->text_domain), __( 'Error', $this->text_domain ), array(
                'response' => 403,
                'back_link' => 'admin.php?page=quiz'
            ) );
        }
    }

    function save_attributes() {
        if ( isset( $_POST[ '_attributes_nonce' ] ) && wp_verify_nonce( $_POST[ '_attributes_nonce' ], 'quiz_save_attributes_form' ) && check_admin_referer( 'quiz_save_attributes_form', '_attributes_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';
            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-schema.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            // Populate the attributes array with user input.
            // Don't worry about input sanitisation for now, as this is handled
            // by the API.
            $attributes = array();
            for ( $i = 1; $i <= sizeof( Quiz_Database_Schema::ATTRIBUTE_SCHEMA ); $i++ ) {
                if ( $_POST[ $i ] == 1 ) {
                    array_push( $attributes, $i );
                }
            }

            $organisation_id = $_POST[ 'organisation_id' ];
            if ( ! $api->replace_attributes_organisation( $organisation_id, $attributes ) ) {
                $this->database_error_redirect( 'organisation-attributes' );
            }

            // Inform the user of the successful form submission.
            $this->save_settings_redirect( 'organisation-attributes' );
            exit;
        } else {
            // Invalid submission.
            wp_die( __( 'Invalid submission.', $this->text_domain), __( 'Error', $this->text_domain ), array(
                'response' => 403,
                'back_link' => 'admin.php?page=organisation-attributes'
            ) );
        }
    }

    function save_attributes_answers() {
        if ( isset( $_POST[ '_attributes_answers_nonce' ] ) && wp_verify_nonce( $_POST[ '_attributes_answers_nonce' ], 'quiz_save_attributes_answers_form' ) && check_admin_referer( 'quiz_save_attributes_answers_form', '_attributes_answers_nonce' ) ) {
            // Valid submission.

            // Extract just the multi-select boxes.
            $selects = array();
            foreach ( $_POST as $key => $value ) {
                if ( strpos( $key, 'quiz_Q' ) !== false ) {
                    $selects[ $key ] = $value;
                }
            }

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            foreach ( $selects as $key => $value ) {
                // Get the answer ID.
                $matches = array();
                preg_match_all('/([0-9]+)/', $key, $matches);
                $i = $matches[ 0 ][ 0 ];
                $j = $matches[ 0 ][ 1 ];
                $answer_id = $api->get_answer_id( $j, $i );

                // Remove all previously set values.
                $api->delete_attribute_answer( $answer_id );

                // Get each attribute and populate the bee_quiz_attributes_relationships table.
                foreach ( $value as $attribute_id ) {
                    if ( ! $api->replace_attribute_answer( $attribute_id, $answer_id ) ) {
                        $this->database_error_redirect( 'assign-attributes' );
                    }
                }
            }

            // Inform the user of the successful form submission.
            $this->save_settings_redirect( 'assign-attributes' );
            exit;
        } else {
            // Invalid submission.
            wp_die( __( 'Invalid submission.', $this->text_domain), __( 'Error', $this->text_domain ), array(
                'response' => 403,
                'back_link' => 'admin.php?page=attributes'
            ) );
        }
    }

    function save_settings_redirect( $page ) {
        wp_redirect( esc_url_raw( add_query_arg( array(
            'notice' => 'success'
        ), admin_url('admin.php?page='. $page ) ) ) );
    }

    function database_error_redirect( $page ) {
        wp_die( __( 'One or more inputs were invalid. Please check all fields and try again.', $this->text_domain), __( 'Error', $this->text_domain ), array(
            'response' => 403,
            'back_link' => 'admin.php?page=' . $page
        ) );
    }

}