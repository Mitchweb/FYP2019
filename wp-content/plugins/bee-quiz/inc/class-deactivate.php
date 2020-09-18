<?php

/**
 * @package BeeQuiz
 */

class Quiz_Deactivate
{
    public static function deactivate() {
        flush_rewrite_rules();

        require_once plugin_dir_path( __FILE__ ) . 'database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            if ( method_exists( Quiz_Database_API::class, 'create_tables' ) ) {
                Quiz_Database_API::drop_tables();
            }
        }
    }
}
