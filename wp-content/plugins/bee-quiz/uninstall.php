<?php

// Trigger this file on plugin uninstall.

if ( ! defined('WP_UNINSTALL_PLUGIN' ) ) {
  die;
}

// Delete the databases related to this plugin.

global $wpdb;

$questions = $wpdb->prefix . 'quiz_questions';
$answers = $wpdb->prefix . 'quiz_answers';

$sql = "DROP TABLE IF EXISTS $questions;";
$wpdb->query( $sql );

$sql = "DROP TABLE IF EXISTS $answers;";
$wpdb->query( $sql );