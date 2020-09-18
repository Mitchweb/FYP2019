<?php

/**
 * @package BeeQuiz
 */

class Quiz_Properties
{
  public $prefix, $path, $url, $basename, $text_domain;

  public function __construct() {
    $this->prefix = 'quiz';
    $this->path = plugin_dir_path( dirname( __FILE__, 1 ) );
    $this->url = plugin_dir_url( dirname( __FILE__, 1 ) );
    $this->basename = 'bee-quiz/bee-quiz.php';
    $this->text_domain = 'bee-quiz';
  }
}
