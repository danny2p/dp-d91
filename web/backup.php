<?php
// Create backup through Workflow API
// 24 hours, database only.
$data = json_encode(
  [
    'type' => 'do_export',      // Workflow type.
    'params' => [
      "entry_type" => "backup", // Required, do not change.
      "ttl" => 86400,           // Backup retention in seconds - default is 24 hours.
      "files" => true,          // Set to true to backup files
      "code" => true,           // Set to true to backup code
      "database" => true        // Set to true to backup database
    ]                           // If ANY of the 3 elements (files, code, db) are not backed up, the one click restore button will not be available.
  ]
);

echo "--- Start workflow: backup -- \n\n";
$result = pantheon_curl('https://api.live.getpantheon.com/sites/self/environments/self/workflows', $data, 8443, 'POST');
echo "--- End workflow: backup -- \n\n";