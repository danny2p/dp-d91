<?php
// SFTP connection details
$sftpHost = 'appserver.danny.b5122a14-30ce-45a2-9026-2b2c739d61ef.drush.in';
$sftpPort = 2222; // Default SFTP port
$username = 'danny.b5122a14-30ce-45a2-9026-2b2c739d61ef';
/*

// Function to simulate fetching the private key from a secrets manager
function getPrivateKeyFromSecretsManager() {
    // Replace this with your actual secrets manager code
    $privateKey = pantheon_get_secret('danny-secret-privkey');
    return $privateKey;
}

*/


$privateKeyFile = pantheon_get_secret('danny-secret-privkey');

// Remote file details
$remoteFile = '/files/themes/twentytwentytwo/assets/images/flight-path-on-transparent-d.png';
$localFile = '/files/themes/twentytwentytwo/assets/images/flight-path-on-transparent-d.png';

// Initialize the SSH connection
$connection = ssh2_connect($sftpHost, $sftpPort);
if (!$connection) {
    die('Connection failed');
}

// Authenticate using the private key
if (!ssh2_auth_pubkey_file($connection, $username, $privateKeyFile . '.pub', $privateKeyFile)) {
    die('Authentication failed');
}

// Initialize SFTP
$sftp = ssh2_sftp($connection);
if (!$sftp) {
    die('SFTP initialization failed');
}

// Open the remote file for reading
$remoteFileStream = fopen("ssh2.sftp://$sftp$remoteFile", 'r');
if (!$remoteFileStream) {
    die('Failed to open remote file');
}

// Open the local file for writing
$localFileStream = fopen($localFile, 'w');
if (!$localFileStream) {
    fclose($remoteFileStream);
    die('Failed to open local file');
}

// Copy the remote file to the local file
$writtenBytes = stream_copy_to_stream($remoteFileStream, $localFileStream);
if ($writtenBytes === false) {
    fclose($remoteFileStream);
    fclose($localFileStream);
    die('Failed to copy remote file to local file');
}

// Close the file streams
fclose($remoteFileStream);
fclose($localFileStream);

echo 'File fetched successfully';