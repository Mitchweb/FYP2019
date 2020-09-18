<?php

/**
 * @package BeeQuiz
 */

class Quiz_Admin_Model extends Quiz_Properties
{
    public $capability, $menu_slug_quiz, $menu_slug_attributes, $menu_slug_assign_attributes;

    public function __construct() {
        parent::__construct();
        $this->capability = 'manage_options';
        $this->menu_slug_quiz = $this->prefix;
        $this->menu_slug_attributes = 'organisation-attributes';
        $this->menu_slug_assign_attributes = 'assign-attributes';
    }

    public function get_page_quiz() {
        require_once plugin_dir_path( __FILE__ ) . '../../templates/quiz.php';
    }

    public function get_page_organisation_attributes() {
        require_once plugin_dir_path( __FILE__ ) . '../../templates/organisation-attributes.php';
    }

    public function get_page_assign_attributes() {
        require_once plugin_dir_path( __FILE__ ) . '../../templates/assign-attributes.php';
    }

    public function get_menu_pages() {
        return array(
            array(
                'page_title' => 'Bee Quiz',
                'menu_title' => 'Bee Quiz',
                'capability' => $this->capability,
                'menu_slug'  => $this->menu_slug_quiz,
                'function'   => array( $this, 'get_page_quiz' ),
                'icon_url'   => 'dashicons-list-view',
                'position'   => 30
            ),
            array(
                'page_title' => 'Bee Organisation Attributes',
                'menu_title' => 'Bee Organisation Attributes',
                'capability' => $this->capability,
                'menu_slug'  => $this->menu_slug_attributes,
                'function'   => array( $this, 'get_page_organisation_attributes' ),
                'icon_url'   => 'dashicons-chart-bar',
                'position'   => 31
            ),
            array(
                'page_title' => 'Bee Assign Attributes',
                'menu_title' => 'Bee Assign Attributes',
                'capability' => $this->capability,
                'menu_slug'  => $this->menu_slug_assign_attributes,
                'function'   => array( $this, 'get_page_assign_attributes' ),
                'icon_url'   => 'dashicons-chart-bar',
                'position'   => 32
            )
        );
    }

    public function get_submenu_pages() {
        return array(
            array(
                'parent_slug' => $this->menu_slug_quiz,
                'page_title'  => 'Bee Quiz',
                'menu_title'  => 'Bee Quiz',
                'capability'  => $this->capability,
                'menu_slug'   => $this->menu_slug_quiz,
                'function'    => array( $this, 'get_page_quiz' )
            ),
            array(
                'parent_slug' => $this->menu_slug_attributes,
                'page_title'  => 'Bee Organisation Attributes',
                'menu_title'  => 'Bee Organisation Attributes',
                'capability'  => $this->capability,
                'menu_slug'   => $this->menu_slug_attributes,
                'function'    => array( $this, 'get_page_organisation_attributes' )
            ),
            array(
                'parent_slug' => $this->menu_slug_assign_attributes,
                'page_title'  => 'Bee Assign Attributes',
                'menu_title'  => 'Bee Assign Attributes',
                'capability'  => $this->capability,
                'menu_slug'   => $this->menu_slug_assign_attributes,
                'function'    => array( $this, 'get_page_assign_attributes' )
            )
        );
    }
}
