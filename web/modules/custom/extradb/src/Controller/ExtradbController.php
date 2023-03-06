<?php
namespace Drupal\extradb\Controller;

class ExtradbController {
  public function extradb() {
    return array (
      '#markup' => 'Welcome to our Website.'
    );
  }
}

//To get another database (here : 'second')
$con = \Drupal\Core\Database\Database::getConnection('default','second');

//To set the active connection
$conn = \Drupal\Core\Database\Database::setActiveConnection('second');