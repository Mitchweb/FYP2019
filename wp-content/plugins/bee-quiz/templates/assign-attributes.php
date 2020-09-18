<div class="wrap">

    <?php

    if ( ! current_user_can( 'manage_options' ) ) :

    ?>

        <div id="error-page">
            <p>Sorry, you are not allowed to access this page.</p>
        </div>

    <?php

    else :

    ?>

        <img src="//placehold.it/290x100" />
        <h1>Assign Attributes</h1>

        <?php

        require_once plugin_dir_path( __FILE__ ) . '../inc/database/class-database-api.php';
        require_once plugin_dir_path( __FILE__ ) . '../inc/database/class-database-schema.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="quiz_save_attributes_answers_form">
            <input type="hidden" name="action" value="quiz_save_attributes_answers_form" />

            <?php

            wp_nonce_field( 'quiz_save_attributes_answers_form', '_attributes_answers_nonce' );

            $questions = $api->get_questions();

            if ( sizeof( $questions ) == 0 ) :
                echo '<p id="quiz-info-note">No questions exist yet.</p>';
            else :
                // Only output questions if there are questions in the database.
                foreach ( $questions as $key => $value ) :

                    $i = $value->question_number;

            ?>

                    <div id="question-<?php echo $i; ?>-container" class="question-container">
                        <h2><?php echo $api->get_question( $i ) ?></h2>
                        <table class="form-table">
                            <tbody>

                                <?php

                                $count_answers = intval( $api->get_count_answers( $i ) );

                                if ( $count_answers != 0 ) :
                                    // Only output answers if there are answers in the database.
                                    for ( $j = 1; $j <= $count_answers; $j++ ) :

                                ?>

                                    <tr>
                                        <th scope="row">
                                            <label for="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>"><?php echo $api->get_answer( $j, $i ); ?></label>
                                        </th>
                                        <td>
                                            <select name="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>[]" id="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>" multiple>

                                                <?php

                                                // Get the answer ID for checking if this option is selected.
                                                $answer_id = $api->get_answer_id( $j, $i );
                                                // Get attributes.
                                                $attributes = $api->get_attributes();
                                                foreach ( $attributes as $attribute ) :

                                                    $is_selected = false;
                                                    if ( ! is_null( $api->get_attribute_answer( $attribute->attribute_id, $answer_id ) ) ) {
                                                        // This attribute_id, answer_id pair exists in the database.
                                                        $is_selected = true;
                                                    }

                                                ?>

                                                    <option value="<?php echo $attribute->attribute_id; ?>" <?php if ( $is_selected ) : ?>selected="selected"<?php endif; ?>><?php echo $attribute->attribute_name; ?></option>

                                                <?php

                                                endforeach;

                                                ?>

                                            </select>

                                        </td>
                                    </tr>

                                <?php

                                    endfor;

                                endif;

                                ?>

                            </tbody>
                        </table>
                    </div>

            <?php

                endforeach;

            endif;

            ?>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

    <?php

    endif;

    ?>

</div>