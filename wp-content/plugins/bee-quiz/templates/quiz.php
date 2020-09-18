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

        <?php

        require_once plugin_dir_path( __FILE__ ) . '../inc/database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            $api = new Quiz_Database_API();
        }

        ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="quiz_save_settings_form">
            <input type="hidden" name="action" value="quiz_save_settings_form" />

            <?php

            wp_nonce_field( 'quiz_save_settings_form', '_quiz_nonce' );

            $questions = $api->get_questions();

            if ( sizeof( $questions ) == 0 ) :
                echo '<p id="quiz-info-note">No questions exist yet. Click \'Add Question\' below to begin adding some.</p>';
            else :
                // Only output questions if there are questions in the database.
                foreach ( $questions as $key => $value ) :

                    $i = $value->question_number;

            ?>

                    <div id="question-<?php echo $i; ?>-container" class="question-container">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <label for="quiz_Q<?php echo $i; ?>_title" class="disabled">Title</label>
                                    </th>
                                    <td>
                                        <input name="quiz_Q<?php echo $i; ?>_title" type="text" id="quiz_Q<?php echo $i; ?>_title" value="<?php echo $api->get_question( $i ) ?>" class="regular-text" disabled="disabled" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="quiz_Q<?php echo $i; ?>_appears_after" class="disabled">Appears after the following answers (IDs shown):</label>
                                    </th>
                                    <td>
                                        <select name="quiz_Q<?php echo $i; ?>_appears_after" id="quiz_Q<?php echo $i; ?>_appears_after" class="appears-after" multiple disabled>
                                    
                                            <?php

                                            // TODO: Answer IDs.

                                            ?>
                                    
                                        </select>
                                    </td>
                                </tr>

                                <?php

                                $count_answers = intval( $api->get_count_answers( $i ) );

                                if ( $count_answers != 0 ) :
                                    // Only output answers if there are answers in the database.
                                    for ( $j = 1; $j <= $count_answers; $j++ ) :

                                        $id = $api->get_answer_id( $j, $i );

                                ?>

                                    <tr>
                                        <th scope="row">
                                            <label for="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>" class="disabled">Answer <?php echo $j; ?></label>
                                        </th>
                                        <td>
                                            <input name="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>" type="text" id="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>" value="<?php echo $api->get_answer( $j, $i ); ?>" class="regular-text" disabled="disabled" /><span id="quiz_Q<?php echo $i; ?>_A<?php echo $j; ?>_id" class="answer-id">ID: <?php echo $id; ?></span>
                                        </td>
                                    </tr>

                                <?php

                                    endfor;

                                endif;

                                ?>

                            </tbody>
                        </table>
                        <div class="edit-question-container">
                            <p id="delete-question-target" class="button button-delete-question" data-question="<?php echo $i; ?>">Delete Question</p>
                            <p id="update-question-target" class="button button-update-question" data-question="<?php echo $i; ?>">Update Question</p>
                        </div>
                    </div>

            <?php

                endforeach;

            endif;

            ?>

            <p id="add-question-target" class="button button-secondary">Add Question</p>
        </form>

    <?php

    endif;

    ?>

</div>