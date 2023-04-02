<?php
// Set the remote PDF URL
$remotePdfUrl = 'https://documents.tocris.com/pdfs/tocris_msds/0106_sds.pdf';

// Get the contents of the remote PDF
$pdfContents = file_get_contents($remotePdfUrl);

// Set the content type and content disposition headers
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="0106_sds.pdf"');

// Serve the PDF to the browser
echo $pdfContents;
?>