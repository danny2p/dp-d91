<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
$autoloader = require_once 'autoload.php';
/*


$pdf_url = "https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf";
// Set the URL of the QuotaGuard proxy server
$proxy_host = 'us-east-static-06.quotaguard.com';
$proxy_port = "9293";
// Set the proxy authentication credentials
$proxy_auth = 'zb435sl185kgoc:nixn57lxz8nxj4jd7rcpdvptnlyf';

$context = stream_context_create([
    'http' => [
        'proxy' => "tcp://{$proxy_host}:{$proxy_port}",
        'request_fulluri' => true,
        'header' => "Proxy-Authorization: Basic " . base64_encode($proxy_auth),
    ]
]);

$filename = "0106_sds.pdf";
$filepath = "https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf";

$response = new Response();
$filename = str_replace('/', '-', $filename);
$disposition = $response->headers->makeDisposition(
  ResponseHeaderBag::DISPOSITION_INLINE,
  $filename
);

$response->headers->set('Content-Disposition', $disposition);
$response->headers->set('Content-Type', 'application/pdf');

$file = fopen($filepath, 'r', false, $context);
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
echo $response;
*/

$pdf_url = "https://documents.tocris.com/pdfs/tocris_msds/0107_sds.pdf";

$quotaguard_env = "https://zb435sl185kgoc:nixn57lxz8nxj4jd7rcpdvptnlyf@us-east-static-06.quotaguard.com:9293";
$quotaguard = parse_url($quotaguard_env);

$proxy_url = $quotaguard['host'].":".$quotaguard['port'];
$proxyAuth = $quotaguard['user'].":".$quotaguard['pass'];

  // Create a new cURL handle
  $ch = curl_init();
  
  // Set the cURL options
  curl_setopt($ch, CURLOPT_URL, $pdf_url);
  curl_setopt($ch, CURLOPT_PROXY, $proxy_url);
  curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
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
  
  // Set the content disposition header
  header('Content-Disposition: attachment; filename=0106_sds.pdf');
  
  // Output the PDF file
  echo $body;
  
  // Close the cURL handle
  curl_close($ch);;






/*

  $pdf_url = '"https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf';
  // Set the URL of the QuotaGuard proxy server
  $proxy_url = 'https://us-east-static-06.quotaguard.com:9292';
  
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

  // Send the response to the browser
$response->send();

*/
  
  ?>