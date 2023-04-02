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
  public function filetest() {

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

  public function filetestqg() {
    
    $pdf_url = 'https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf';
    // Set the URL of the QuotaGuard proxy server
    $proxy_url = 'http://us-east-static-06.quotaguard.com:9293';
    
    // Set the proxy authentication credentials
    $username = 'zb435sl185kgoc';
    $password = 'nixn57lxz8nxj4jd7rcpdvptnlyf';

    // Create a new cURL handle
    $ch = curl_init();

    // Set the cURL options
    curl_setopt($ch, CURLOPT_URL, $pdf_url);
    curl_setopt($ch, CURLOPT_PROXY, $proxy_url);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf'));

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_error($ch)) {
        echo 'Error: ' . curl_error($ch);
        exit;
    }

    // Get the response header size
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    // Get the response headers
    $headers = substr($response, 0, $header_size);

    // Get the response body
    $body = substr($response, $header_size);

    // Close the cURL handle
    curl_close($ch);

    // Create a new response object
    $response = new Response($body);

    // Set the content disposition header
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'example.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    // Set the content type header
    $response->headers->set('Content-Type', 'application/pdf');
    return $response;
      
    return [
      '#markup' => $this->t($disposition),
    ];
  }

}
