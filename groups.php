<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require('vendor/autoload.php');

// Logged in user's email
$email = 'user@domain.ge';

$client = new Google_Client();
$client->setApplicationName('User Groups');
$client->setAuthConfig('authorization.json');
$client->setAccessType('offline');
// Add scope: https://www.googleapis.com/auth/admin.directory.group.readonly
$client->addScope(Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY);
$client->setSubject('service-account@domain.ge');

$service = new Google_Service_Directory($client);
$groups = $service->groups->listGroups([
    'domain'     => 'domain.ge',
    'userKey'    => $email,
    'maxResults' => '50',
]);

$groups_list = [];
/** @var Google_Service_Directory_Group $group */
foreach ($groups as $group) {
    $groups_list[] = $group->getName();
}

echo 'E-Mail: ' . $email . '<br />';
echo 'Groups: ' . implode(', ', $groups_list) . '<br />';
