<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
session_start();
require('vendor/autoload.php');

$path = dirname(__FILE__);

$client = new Google_Client();
$client->setApplicationName('Google Auth');
$client->setAuthConfig($path . '/google_client_secret.json');
$client->setRedirectUri($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->setAccessType('offline');
$client->addScope(Google_Service_Oauth2::PLUS_ME);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY);