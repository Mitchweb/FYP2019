<?php

/**
 * @package BeeQuiz
 */

class Quiz_CSS extends Quiz_Properties
{
    public function init() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_css' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_css' ) );
    }

    function enqueue_admin_css() {
        wp_enqueue_style( $this->prefix . '-admin-css', plugin_dir_url( __FILE__ ) . '../../assets/css/admin.min.css' );
    }

    function enqueue_frontend_css() {
        wp_enqueue_style( $this->prefix . '-frontend-css', plugin_dir_url( __FILE__ ) . '../../assets/css/frontend.min.css' );
    }
}