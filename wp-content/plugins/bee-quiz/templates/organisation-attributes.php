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

        $organisations = $api->get_organisations();

        $organisation_id = $api->get_first_organisation();
        $attributes = $api->get_attributes_organisation( $organisation_id );

        // Populate the data variable.
        $data = array();
        foreach ( Quiz_Database_Schema::ATTRIBUTE_SCHEMA as $key => $value ) {
            $data[ $key ] = array(
                'attribute_id' => $api->get_attribute_id( $key ),
                'attribute_name_pretty' => $value,
            );
        }

        ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="quiz_save_attributes_form">
            <input type="hidden" name="action" value="quiz_save_attributes_form" />

            <?php

            wp_nonce_field( 'quiz_save_attributes_form', '_attributes_nonce' );

            ?>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label>Switch Organisation</label>
                        </th>
                        <td>
                            <select name="organisation_id" id="switch-organisation">

                                <?php

                                foreach ( $organisations as $organisation ) :

                                ?>

                                    <option value="<?php echo $organisation->organisation_id; ?>"><?php echo $organisation->organisation_name; ?></option>

                                <?php

                                endforeach;

                                ?>

                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table id="quiz_save_attributes_table" class="form-table">
                <tbody>

                    <?php

                    // Initialise loop variables.
                    $end = 0;

                    foreach ( Quiz_Database_Schema::ATTRIBUTE_DESCRIPTION_SCHEMA as $key => $value ) :

                    ?>

                        <tr>
                            <th scope="row">
                                <?php echo $value[ 'category' ]; ?>
                            </th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php echo $value[ 'category' ]; ?></span>
                                    </legend>

                                    <?php

                                    $start = $end;
                                    $i = 0;
                                    $end += $value[ 'fields' ];

                                    foreach ( $data as $key => $value ) :

                                        if ( $i < $start ) {
                                            $i++;
                                            continue;
                                        }

                                        if ( $i >= $end ) {
                                            break;
                                        }

                                    ?>

                                        <label for="<?php echo $value[ 'attribute_id' ]; ?>">
                                            <select name="<?php echo $value[ 'attribute_id' ]; ?>" id="<?php echo $value[ 'attribute_id' ]; ?>">
                                                <option value="1" <?php if ( in_array( $value[ 'attribute_id'], $attributes ) ) : ?>selected<?php endif; ?>>Yes</option>
                                                <option value="0" <?php if ( ! in_array( $value[ 'attribute_id'], $attributes ) ) : ?>selected<?php endif; ?>>No</option>
                                            </select>
                                            <?php echo $value[ 'attribute_name_pretty' ]; ?>
                                        </label>
                                        <br />

                                    <?php

                                        $i++;

                                    endforeach;

                                    ?>

                                </fieldset>
                            </td>
                        </tr>

                    <?php

                    endforeach;

                    ?>

                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

    <?php

    endif;

    ?>

</div>