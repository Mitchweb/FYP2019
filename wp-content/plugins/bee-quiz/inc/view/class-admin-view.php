<?php

/**
 * @package BeeQuiz
 */

/**
 * Manages the options available to the user in the WordPress admin.
 */
class Quiz_Admin_View extends Quiz_Properties
{
  public function init() {
    // We'll need the Quiz_Admin_Model for later.
    require_once plugin_dir_path( __FILE__ ) . '../model/class-admin-model.php';
    add_action( 'admin_menu', array( $this, 'add_menu_pages') );
  }

  public function add_menu_pages() {
    if ( class_exists( 'Quiz_Admin_Model' ) ) {
      $admin_model = new Quiz_Admin_Model();

      if ( method_exists( $admin_model, 'get_menu_pages' ) ) {
        foreach ( $admin_model->get_menu_pages() as $p) {
          add_menu_page( $p['page_title'], $p['menu_title'], $p['capability'], $p['menu_slug'], $p['function'], $p['icon_url'], $p['position'] );
        }
      }

      if ( method_exists( $admin_model, 'get_submenu_pages' ) ) {
        foreach ( $admin_model->get_submenu_pages() as $p) {
          add_submenu_page( $p['parent_slug'], $p['page_title'], $p['menu_title'], $p['capability'], $p['menu_slug'], $p['function'] );
        }
      }
    }
  }

}
