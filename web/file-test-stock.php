<?php

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
$autoloader = require_once 'autoload.php';

$filename = "0106_sds.pdf";
$response = new Response();

$filename = str_replace('/', '-', $filename);
$disposition = $response->headers->makeDisposition(
  ResponseHeaderBag::DISPOSITION_INLINE,
  $filename
);
$response->headers->set('Content-Disposition', $disposition);
$response->headers->set('Content-Type', 'application/pdf');


$pdf_url = "https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf";

$quotaguard_env = "https://zb435sl185kgoc:nixn57lxz8nxj4jd7rcpdvptnlyf@us-east-static-06.quotaguard.com:9293";
$quotaguard = parse_url($quotaguard_env);

$proxyUrl = $quotaguard['host'].":".$quotaguard['port'];
$proxyAuth = $quotaguard['user'].":".$quotaguard['pass'];


#$url = "https://pantheon.io";
$url = $pdf_url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxyUrl);
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
    echo $response;




?>