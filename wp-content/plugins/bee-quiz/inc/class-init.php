<?php

/**
 * @package BeeQuiz
 */

final class Quiz_Init
{
  /**
   * Loop through the classes, instantiate them, and call the init() method
   * if it exists.
   */
  public static function init() {
    foreach ( self::get_classes() as $class ) {
      $class_to_init = new $class();
      if ( method_exists( $class_to_init, 'init' ) ) {
        $class_to_init->init();
      }
    }
  }

  /**
   * Store all the classes inside an array.
   * @return array Full list of classes
   */
  public static function get_classes() {
    require_once plugin_dir_path( __FILE__ ) . 'model/class-admin-forms.php';
    require_once plugin_dir_path( __FILE__ ) . 'model/class-admin-notices.php';
    require_once plugin_dir_path( __FILE__ ) . 'view/class-admin-view.php';
    require_once plugin_dir_path( __FILE__ ) . 'view/class-css.php';
    require_once plugin_dir_path( __FILE__ ) . 'view/class-js.php';
    require_once plugin_dir_path( __FILE__ ) . 'view/class-shortcodes.php';

    return [
      Quiz_Admin_Forms::class,
      Quiz_Admin_Notices::class,
      Quiz_Admin_View::class,
      Quiz_CSS::class,
      Quiz_JS::class,
      Quiz_Shortcodes::class
    ];
  }
}
