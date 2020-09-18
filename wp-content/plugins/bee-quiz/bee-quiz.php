<?php

/*
 * Plugin Name: Bee Quiz
 * Plugin URI: https://git.cs.bham.ac.uk/mxc413/studentbee
 * Description: Handles the quiz on the StudentBee website.
 * Version: 1.0.0
 * Author: Mitchell Clarkson
 * Author URI: https://git.cs.bham.ac.uk/mxc413
 * Text Domain: bee-quiz
 */

/**
 * @package BeeQuiz
 */

if ( ! defined('ABSPATH') ) {
  die;
}

/**
 * Runs during plugin activation.
 */
function activate_bee_quiz() {
  require_once plugin_dir_path( __FILE__ ) . 'inc/class-activate.php';
  Quiz_Activate::activate();
}
register_activation_hook( __FILE__, 'activate_bee_quiz' );

/**
 * Runs during plugin deactivation.
 */
function deactivate_bee_quiz() {
  require_once plugin_dir_path( __FILE__ ) . 'inc/class-deactivate.php';
  Quiz_Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_bee_quiz' );

/**
 * Initialize the core classes of the plugin.
 */

if ( ! class_exists( 'Quiz_Init' ) ) {
  // Ensure Properties are loaded.
  require_once plugin_dir_path( __FILE__ ) . 'inc/class-properties.php';
  require_once plugin_dir_path( __FILE__ ) . 'inc/class-init.php';
  Quiz_Init::init();
}