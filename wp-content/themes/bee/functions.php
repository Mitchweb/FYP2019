<?php

/**
 * StudentBee Theme Functions
 * @package bee
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! function_exists( 'bee_setup' ) ) {
    function bee_setup() {
        load_theme_textdomain( 'bee', get_template_directory() . '/languages' );
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary'   => __( 'Primary Menu', 'bee' ),
            'secondary' => __( 'Secondary Menu', 'bee' )
        ) );
        add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );
    }
}
add_action( 'after_setup_theme', 'bee_setup' );

if ( ! function_exists( 'bee_enqueue_scripts_styles' ) ) {
    function bee_enqueue_scripts_styles() {
        wp_enqueue_style( 'bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
        wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Laila:600|Source+Sans+Pro' );
        wp_enqueue_style( 'icons', 'https://use.fontawesome.com/releases/v5.7.2/css/all.css' );
        wp_enqueue_style( 'bee-css', get_bloginfo( 'stylesheet_directory' ) . '/assets/css/bee.min.css' );

        wp_enqueue_script( 'popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array( 'jquery' ), '', true);
        wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'bee-js', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/bee.min.js', array( 'jquery' ), '', true );
    }
}
add_action( 'wp_enqueue_scripts', 'bee_enqueue_scripts_styles' );