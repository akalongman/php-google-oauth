<?php

require('inc.php');

if (isset($_GET['logout'])) {
    $client->revokeToken();
    session_destroy();
    $redirect_uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    die;
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    $service = new Google_Service_Oauth2($client);
    $user = $service->userinfo->get();

    $groups_list = [];
    try {
        $service = new Google_Service_Directory($client);
        $groups = $service->groups->listGroups([
            'domain'     => 'mydomain.ge',
            'userKey'    => $user->getId(),
            'maxResults' => '50',
        ]);

        /** @var Google_Service_Directory_Group $group */
        foreach ($groups as $group) {
            $groups_list[] = $group->getName();
        }
    } catch (Throwable $e) {
        dump($e->getMessage());
    }
    echo '<b>Logged In!</b><br />';
    echo 'E-Mail: ' . $user->getEmail() . '<br />';
    echo 'Groups: ' . implode(', ', $groups_list) . '<br />';
    echo '<a href="?logout">Logout</a>';
} else {
    if (isset($_GET['login'])) {
        $redirect_uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    } else {
        echo '<a href="?login">Login</a>';
    }
}