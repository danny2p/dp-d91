<?php

function load_git_secrets($git_secrets_file)
{
  if (!file_exists($git_secrets_file)) {
    print "Could not find $git_secrets_file\n";
    return [];
  }
  $git_secrets_contents = file_get_contents($git_secrets_file);
  if (empty($git_secrets_contents)) {
    print "GitHub secrets file is empty\n";
    return [];
  }
  $git_secrets = json_decode($git_secrets_contents, true);
  if (empty($git_secrets)) {
    print "No data in Git secrets\n";
  }
  return $git_secrets;
}

print "Starting Quicksilver Script \n\n";

print "POST: <pre>";
print_r($_POST);
print "</pre>";

# If you only wanted this to execute on Dev (master):
if ($_ENV['PANTHEON_ENVIRONMENT'] != "autopilot") {
  return;
}


$private_files = realpath($_SERVER['HOME']."/files/private");
$git_secrets_file = "$private_files/.build-secrets/tokens.json";
$git_secrets = load_git_secrets($git_secrets_file);
$git_token = $git_secrets['token'];

if (empty($git_token)) {
    $message = "Unable to load Git token from secrets file \n";
    print $message;
    return;
}

/*
*
* Since Pantheon is really authoritative, in the sense that we're running the code, 
* we'll try to automatically push back to Github master.
* In most cases this should be safe if commits to Github master
* branch are always being pushed to Pantheon.
* extend this logic as necessary to fit your needs.
*
*/

$github_remote="https://danny2p:$git_token@github.com/danny2p/dp-d91.git";
echo "pwd: ";
echo exec("pwd");
echo "\n ls -lha: \n";
echo exec("ls -lha");

echo "cd ../../";
echo exec ("cd ../../");
echo "\n\n git status: \n";
echo exec("git status");
echo "\n git log: \n";
echo exec("git log");
echo "\n\n";
echo exec("git pull $github_remote autopilot");
echo exec("git push --set-upstream $github_remote autopilot");
print "\n Pushed to remote repository.";
