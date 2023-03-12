<?php
namespace Drupal\extradb\Controller;

class ExtradbController {
  public function extradb() {
    $con = \Drupal\Core\Database\Database::getConnection('default','second');
    $sql = "SELECT id, pid, price FROM {products}";
    #$sql = "show tables";
    #$database = \Drupal::database();
    $query = $con->query($sql);
    $result = $query->fetchAll();

    $output = "<pre>";
    $output .= print_r($result,true);
    $output .= "</pre>";

    return array (
      '#markup' => "Product data fetched from external database: " . $output
    );
  }
}
