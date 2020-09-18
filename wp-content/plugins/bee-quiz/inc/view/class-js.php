<?php

/**
 * @package BeeQuiz
 */

class Quiz_JS extends Quiz_Properties
{
    public function init() {
        add_action( 'admin_footer', array( $this, 'enqueue_admin_js' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_js' ) );

        add_action( 'wp_ajax_quiz_get_max_question_number', array( $this, 'quiz_get_max_question_number' ) );

        add_action( 'wp_ajax_quiz_fill_appears_after', array( $this, 'quiz_fill_appears_after' ) );

        add_action( 'wp_ajax_quiz_save_settings_form', array( $this, 'quiz_save_question' ) );

        add_action( 'wp_ajax_quiz_delete_question', array( $this, 'quiz_delete_question') );

        add_action( 'wp_ajax_attributes_switch_organisation', array( $this, 'attributes_switch_organisation' ) );

        add_action( 'wp_ajax_get_question_contents', array( $this, 'get_question_contents' ) );
        add_action( 'wp_ajax_nopriv_get_question_contents', array( $this, 'get_question_contents' ) );

        add_action( 'wp_ajax_get_quiz_results', array( $this, 'get_quiz_results' ) );
        add_action( 'wp_ajax_nopriv_get_quiz_results', array( $this, 'get_quiz_results' ) );

        add_action( 'wp_ajax_submit_registration_form', array( $this, 'submit_registration_form' ) );
        add_action( 'wp_ajax_nopriv_submit_registration_form', array( $this, 'submit_registration_form' ) );
    }

    function enqueue_admin_js() {
        wp_enqueue_script( $this->prefix . '-admin-js', plugin_dir_url( __FILE__ ) . '../../assets/js/admin.min.js', array( 'jquery' ), '', true );
    }

    function enqueue_frontend_js() {
        wp_enqueue_script( $this->prefix . '-postcodes-js', plugin_dir_url( __FILE__ ) . '../../assets/js/postcodes.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( $this->prefix . '-frontend-js', plugin_dir_url( __FILE__ ) . '../../assets/js/frontend.min.js', array( 'jquery' ), '', true );
        
        wp_localize_script( $this->prefix . '-frontend-js', $this->prefix . '_ajax_handler', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ) );
    }

    function quiz_get_max_question_number() {
        // Assume failure.
        $response = 0;

        if ( isset( $_POST[ '_quiz_nonce' ] ) && wp_verify_nonce( $_POST[ '_quiz_nonce' ], 'quiz_save_settings_form' ) && check_ajax_referer( 'quiz_save_settings_form', '_quiz_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            $response = $api->get_max_question_number();
            if ( is_null( $response ) ) {
                $response = 0;
            }
            echo json_encode( $response );
            wp_die();
        } else {
            // Invalid submission.
            echo json_encode( $response );
            wp_die();
        }
    }

    function quiz_fill_appears_after() {
        if ( isset( $_POST[ '_quiz_nonce' ] ) && wp_verify_nonce( $_POST[ '_quiz_nonce' ], 'quiz_save_settings_form' ) && check_ajax_referer( 'quiz_save_settings_form', '_quiz_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            $response->all = $api->get_answer_ids();
            if ( is_null( $response->all ) ) {
                $response->all = 0;
            }

            $question_number = $_POST[ 'question_number' ];

            $response->selected = $api->get_answer_ids( $question_number );
            if ( is_null( $response->selected ) ) {
                $response->selected = 0;
            }

            echo json_encode( $response );
            wp_die();
        } else {
            // Invalid submission.
            echo json_encode( 0 );
            wp_die();
        }
    }

    function quiz_save_question() {
        // Assume failure.
        $response = 0;

        if ( isset( $_POST[ '_quiz_nonce' ] ) && wp_verify_nonce( $_POST[ '_quiz_nonce' ], 'quiz_save_settings_form' ) && check_ajax_referer( 'quiz_save_settings_form', '_quiz_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            $question_string = $_POST[ 'question_string' ];
            $question_number = $_POST[ 'question_number' ];

            if ( ! $api->replace_question( $question_string, $question_number ) ) {
                echo json_encode( $response );
                wp_die();
            }

            $j = 1;
            while ( true ) {
                if ( ! isset( $_POST[ 'answer_string_' . $j ] ) ) {
                    // If we have ran out of answers, exit the loop.
                    break;
                }

                $answer_string = $_POST[ 'answer_string_' . $j ];
                $answer_number = $j;

                if ( ! $api->replace_answer( $answer_string, $answer_number, $question_number ) ) {
                    echo json_encode( $response );
                    wp_die();
                }

                $j++;
            }

            $answer_list = $_POST[ 'answer_list' ];

            if ( ! $api->replace_answer_list( $answer_list, $question_number ) ) {
                echo json_encode( $response );
                wp_die();
            }
            
            $response = array(
                "answer_id_1" => $api->get_answer_id( 1, $question_number ),
                "answer_id_2" => $api->get_answer_id( 2, $question_number ),
                "answer_id_3" => $api->get_answer_id( 3, $question_number )
            );

            echo json_encode( $response );
            wp_die();
        } else {
            // Invalid submission.
            echo json_encode( $response );
            wp_die();
        }
    }

    function quiz_delete_question() {
        // Assume failure.
        $response = 0;

        if ( isset( $_POST[ '_quiz_nonce' ] ) && wp_verify_nonce( $_POST[ '_quiz_nonce' ], 'quiz_save_settings_form' ) && check_ajax_referer( 'quiz_save_settings_form', '_quiz_nonce' ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            $question_string = $_POST[ 'question_string' ];
            $question_number = $_POST[ 'question_number' ];

            if ( ! $api->delete_question( $question_string, $question_number ) ) {
                echo json_encode( $response );
                wp_die();
            }

            $response = 1;
            echo json_encode( $response );
            wp_die();
        } else {
            // Invalid submission.
            echo json_encode( $response );
            wp_die();
        }
    }

    function get_question_contents() {
        $quiz_tracker = $_POST[ 'quiz_tracker' ];

        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        // Get the Question ID to work with, in this instance.
        $question_id = 0;
        if ( is_null( $quiz_tracker[ 'questions' ] ) ) {
            // Our quiz has just started, so we initialise the list of questions.
            $quiz_tracker[ 'questions' ] = array();
            $question_id = $api->get_first_quiz_question();
        } else {
            foreach ( $quiz_tracker[ 'answers' ] as $answer_id ) {
                // Get all eligible questions.
                $tmp_ids = $api->get_next_quiz_question( $answer_id );
                foreach ( $tmp_ids as $tmp_id ) {
                    if ( ! array_search( $tmp_id, $quiz_tracker[ 'questions' ] ) ) {
                        // This question hasn't been shown yet.
                        $question_id = $tmp_id;
                        break;
                    } else {
                        // This question has already been shown.
                        continue;
                    }
                }
            }
        }

        // Update the quiz tracker with our chosen Question ID.
        array_push( $quiz_tracker[ 'questions' ], $question_id );

        // Get the question title and add it to our response.
        $response[ 'question_title' ] = $api->get_quiz_question( $question_id );

        if ( is_null( $quiz_tracker[ 'answers' ] ) ) {
            // Our quiz has just started, so we initialise the list of answers.
            $quiz_tracker[ 'answers' ] = array();
        }

        // Get the answers and add them to our response.
        $answers = $api->get_quiz_answers( $question_id );
        $j = 1;
        foreach ( $answers as $key => $value ) {
            // Update the response.
            $response[ 'answer_' . $j .'_id' ] = $value->answer_id;
            $response[ 'answer_' . $j ] = $value->answer_string;
            $j++;
        }

        $response[ 'quiz_tracker' ] = $quiz_tracker;

        echo json_encode( $response );
        wp_die();
    }

    function attributes_switch_organisation() {
        $organisation_id = $_POST[ 'organisation_id' ];

        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        $attributes = $api->get_attributes_organisation( $organisation_id );

        echo json_encode( $attributes );
        wp_die();
    }

    function get_quiz_results() {
        $answers = $_POST[ 'answers' ];

        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        $response = 0;

        $args = implode( ',', $answers );

        $command = escapeshellcmd( '/Users/mitch/Documents/Websites/bee/wp-content/plugins/bee-quiz/python/csp.py ' . $args );
        $organisation_id = shell_exec( $command ); // This picks up on whatever the Python script kicks out.

        // Send answers to Python script.
        // Python script returns the top organisation.
        // Query the database for this organisation and return values to client.
        $response = $api->get_organisation_details( $organisation_id );

        echo json_encode( $response );
        wp_die();
    }

    function submit_registration_form() {
        // Assume failure.
        $response = 0;

        if ( wp_verify_nonce( $_POST[ '_register_nonce' ], 'submit_registration_form' ) && check_admin_referer( 'submit_registration_form', '_register_nonce', false ) ) {
            // Valid submission.

            require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

            if ( class_exists( 'Quiz_Database_API' ) ) {
                $api = new Quiz_Database_API();
            }

            $name = $_POST[ 'register_organisation_name' ];
            $description = $_POST[ 'register_organisation_description' ];
            $contact_picture = $_FILES[ 'register_organisation_contact_picture' ];
            $contact_name = $_POST[ 'register_organisation_contact_name' ];
            $contact_email_address = $_POST[ 'register_organisation_contact_email_address' ];
            $address_line_1 = $_POST[ 'register_organisation_address_line_1' ];
            $address_line_2 = $_POST[ 'register_organisation_address_line_2' ];
            $address_line_3 = $_POST[ 'register_organisation_address_line_3' ];
            $address_town_city = $_POST[ 'register_organisation_address_town_city' ];
            $address_postcode = $_POST[ 'register_organisation_address_postcode' ];

            if ( ! $api->replace_organisation( $name, $description, $contact_picture, $contact_name, $contact_email_address, $address_line_1, $address_line_2, $address_line_3, $address_town_city, $address_postcode ) ) {
                echo json_encode( $response );
                wp_die();
            }

            $response = 1;
            echo json_encode( $response );
            wp_die();
        } else {
            // Invalid submission.
            echo json_encode( $response );
            wp_die();
        }
    }

    /** DEPRECATED */
    function get_question() {
        $question_number = intval( $_POST[ 'question_number' ] );

        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        $question_string = $api->get_question( $question_number );
        echo json_encode( $question_string );
        wp_die();
    }

    /** DEPRECATED */
    function get_answer() {
        $answer_number = intval( $_POST[ 'answer_number' ] );
        $question_number = intval( $_POST[ 'question_number' ] );

        require_once plugin_dir_path( __FILE__ ) . '../database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        $answer_string = $api->get_answer( $answer_number, $question_number );
        echo json_encode( $answer_string );
        wp_die();
    }
}