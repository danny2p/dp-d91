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
* Let's check for changes in Github
*
* Since Pantheon is really authoritative, as we're running the code, we'll try to automatically
* push back to Github master.  In most cases this should be safe if commits to Github master
* branch are always being pushed to Pantheon.
*
*/

$github_remote="https://danny2p:$git_token@github.com/danny2p/dp-d91.git";

// latest local commit
$local = exec("git rev-parse @");
// latest github commit
$remote = exec('git ls-remote '.$github_remote.' | head -1 | sed "s/HEAD//"');
// check if Pantheon's latest commit to master is a descendent of latest commit in github - if not we probably don't want to push
$is_remote_ancestor = exec("git merge-base --is-ancestor $remote master");
$ancestor_output = $is_remote_ancestor ? 'Github HEAD is not ancestor' : 'Github HEAD is ancestor';

print "Local: $local \n";
print "Remote: $remote \n";
print "$ancestor_output \n";

if ($local == $remote) {
    // in many cases CI, or user pushed to Pantheon, so Pantheon will be up to date with Github
    print "Up-to-date.";
    return;
} elseif ($is_remote_ancestor == 0) {
    // in this case, 0 means true
    // in the case of Autopilot or dashboard one-click update, Pantheon will have new commits.  Github head should be ancestor
    exec("git push $github_remote master");
    print "\n Pushed to Github.";
} else {
    print "Pantheon and Github have diverged'.";
    // TODO - slack notification or other to notify user they may need to manually reconcile
    return;
}
