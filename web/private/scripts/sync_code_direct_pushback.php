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
$github_remote="https://danny2p:$git_token@github.com/danny2p/dp-d91.git";

#exec_print("git fetch https://danny2p:$git_token@github.com/danny2p/dp-d91.git master -vvv");
exec("git remote add github https://danny2p:$git_token@github.com/danny2p/dp-d91.git");
exec("git fetch https://danny2p:$git_token@github.com/danny2p/dp-d91.git master");
/*
$fetch_output = passthru("git fetch https://danny2p:$git_token@github.com/danny2p/dp-d91.git master -vvv");
print "fetch output: $fetch_output \n";
$github_remote="https://danny2p:$git_token@github.com/danny2p/dp-d91.git";
$behind_count = passthru("git rev-list --count HEAD..github/master");
$ahead_count = passthru("git rev-list --count github/master..HEAD");
print "Behind: $behind_count \n";
print "Ahead: $ahead_count \n";

if ($ahead_count > 0 && $behind_count == 0) {
    print "Pushing to Github. \n";
    passthru("git push $github_remote master");
    print "\n Pushed to Github. \n";
}
*/
//latest local commit
$local = exec("git rev-parse @");
//latest github commit
$remote = exec('git ls-remote '.$github_remote.' | head -1 | sed "s/HEAD//"');
$is_remote_ancestor = exec("git merge-base --is-ancestor $remote master");
#$base = passthru("git merge-base @ github/master");

print "Local: $local \n";
print "Remote: $remote \n";
print "Ancestor: $is_remote_ancestor \n";

if ($local == $remote) {
    print "Up-to-date.";
    return;
} elseif ($is_remote_ancestor) {
    exec_print("git push $github_remote master");
    print "\n Pushed to github";
} else {
    print "Pantheon is behind GitHub.";
    return;
}
