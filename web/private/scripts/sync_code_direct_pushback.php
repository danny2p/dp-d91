<?php

function load_git_secrets($gitSecretsFile)
{
  if (!file_exists($gitSecretsFile)) {
    print "Could not find $gitSecretsFile\n";
    return [];
  }
  $gitSecretsContents = file_get_contents($gitSecretsFile);
  if (empty($gitSecretsContents)) {
    print "GitHub secrets file is empty\n";
    return [];
  }
  $gitSecrets = json_decode($gitSecretsContents, true);
  if (empty($gitSecrets)) {
    print "No data in Git secrets\n";
  }
  return $gitSecrets;
}

function exec_print($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}

// Do nothing if not on Pantheon or if on the test/live environments.
if (!isset($_ENV['PANTHEON_ENVIRONMENT']) || in_array($_ENV['PANTHEON_ENVIRONMENT'], ['test', 'live']) ) {
    return;
}

$bindingDir = $_SERVER['HOME'];
$fullRepository = realpath("$bindingDir/code");

$privateFiles = realpath("$bindingDir/files/private");
$gitSecretsFile = "$privateFiles/.build-secrets/tokens.json";
$gitSecrets = load_git_secrets($gitSecretsFile);
$git_token = $gitSecrets['token'];

if (empty($git_token)) {
    $message = "Unable to load Git token from secrets file \n";
    //pantheon_raise_dashboard_error($message, true);
    print $message;
    return;
}

/*
*
* Fetch in case of changes in github master
*
*/

exec_print("git fetch https://danny2p:$git_token@github.com/danny2p/dp-d91.git master");

$github_remote="https://danny2p:$git_token@github.com/danny2p/dp-d91.git";

$local = exec("git rev-parse @");
$remote = exec("git rev-parse $github_remote");
$base = exec("git merge-base @ $github_remote");

if ($local == $remote) {
    print "Up-to-date.";
    return;
} elseif ($local == $base) {
    print "Pantheon is behind GitHub.";
    return;
} elseif ($remote = $base) {
    exec_print("git push $github_remote master");
    print "\n Pushed to github";
} else {
    print "Ancestors have diverged.";
    return;
}