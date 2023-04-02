<?php

namespace Drupal\file_testing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Provides a controller for the File Testing module.
 */
class FileTestingController extends ControllerBase {

  /**
   * Returns the rendered output for the custom "/file-testing" path.
   *
   * @return array
   *   A render array representing the output.
   */
  public function hello() {

    $filename = "0106_sds.pdf";
    $filepath = "https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf";
  
      $response = new Response();
      if (!empty($filename) && !empty($filepath)) {
        $filename = str_replace('/', '-', $filename);
        $disposition = $response->headers->makeDisposition(
          ResponseHeaderBag::DISPOSITION_INLINE,
          $filename
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'application/pdf');
  
        $file = fopen($filepath, 'r');
        if ($file !== FALSE) {
          $content = '';
          while (!feof($file)) {
            $content .= fread($file, 8192);
          }
          fclose($file);
          if (!empty($content)) {
            $response->setContent($content);
          }
          else {
            $response->setStatusCode('404');
          }
        }
        else {
          $response->setStatusCode('404');
        }
      }
    return $response;
      
    return [
      '#markup' => $this->t($disposition),
    ];
  }

}
