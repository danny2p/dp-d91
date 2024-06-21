<?php
require_once('../vendor/autoload.php');
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

// SFTP connection details
$host = 'appserver.danny.b5122a14-30ce-45a2-9026-2b2c739d61ef.drush.in';
$port = 2222; // Default SFTP port
$user = 'danny.b5122a14-30ce-45a2-9026-2b2c739d61ef';
$privateKeyFile = pantheon_get_secret('danny-secret-privkey');

echo "privatekeyfile: <br />". $privateKeyFile;
echo "<hr />";

echo "privatekeyfilecontents: <br />". file_get_contents($privateKeyFile);
echo "<hr />";

/*

// Function to simulate fetching the private key from a secrets manager
function getPrivateKeyFromSecretsManager() {
    // Replace this with your actual secrets manager code
    $privateKey = pantheon_get_secret('danny-secret-privkey');
    return $privateKey;
}

*/




// Remote file details
$remoteFile = '/files/themes/twentytwentytwo/assets/images/flight-path-on-transparent-d.png';
$localFile = '/files/themes/twentytwentytwo/assets/images/flight-path-on-transparent-d.png';

$key = PublicKeyLoader::loadPrivateKey($privateKeyFile);

// Initialize SFTP
$sftp = new SFTP($host, $port);

// Authenticate using the private key
if (!$sftp->login($user, $key)) {
    exit('Login Failed');
}

// Fetch the remote file
if (!$sftp->get($remoteFile, $localFile)) {
    exit('Failed to fetch the remote file');
}
