<?php

/**
 * @package BeeQuiz
 */

class Quiz_Admin_Notices extends Quiz_Properties
{
  public function init() {
    add_action( 'admin_notices', array( $this, 'echo_quiz_notices' ) );
    add_action( 'admin_notices', array( $this, 'echo_attributes_notices' ) );
    add_action( 'admin_notices', array( $this, 'echo_assign_attributes_notices' ) );
  }

  function echo_quiz_notices() {
    if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] === 'quiz' && isset( $_GET[ 'notice' ] ) && $_GET[ 'notice' ] === 'success' ) {
        $html = '<div id="message" class="notice notice-success is-dismissible"><p><strong>Settings saved.</strong></p></div>';
        echo $html;
    }
    return;
  }

  function echo_attributes_notices() {
    if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] === 'organisation-attributes' && isset( $_GET[ 'notice' ] ) && $_GET[ 'notice' ] === 'success' ) {
      $html = '<div id="message" class="notice notice-success is-dismissible"><p><strong>Settings saved.</strong></p></div>';
      echo $html;
    }
    return;
  }

  function echo_assign_attributes_notices() {
    if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] === 'assign-attributes' && isset( $_GET[ 'notice' ] ) && $_GET[ 'notice' ] === 'success' ) {
      $html = '<div id="message" class="notice notice-success is-dismissible"><p><strong>Settings saved.</strong></p></div>';
      echo $html;
    }
    return;
  }
}