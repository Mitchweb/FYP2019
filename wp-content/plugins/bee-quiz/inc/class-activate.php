<?php

/**
 * @package BeeQuiz
 */

class Quiz_Activate
{
    public static function activate() {
        flush_rewrite_rules();

        require_once plugin_dir_path( __FILE__ ) . 'database/class-database-api.php';

        if ( class_exists( 'Quiz_Database_API' ) ) {
            if ( method_exists( Quiz_Database_API::class, 'create_tables' ) ) {
                Quiz_Database_API::create_tables();
            }
        }
    }
}
