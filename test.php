<?php

/**
 * This is just a jumpstart demo
 * @TODO: Implement the real SDK
 */

session_start();
$composer = include_once( 'vendor/autoload.php' );

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$private_info = [
    'base_uri' => 'METABASE-URL',
    'email'    => 'USER-EMAIL',
    'password' => 'USER-PASSWORD',
];


$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => $private_info['base_uri'],
    // You can set any number of default request options.
//    'timeout'  => 2.0,
]);

$headers = [
    'Content-Type' => 'application/json',
];

echo '<pre>';

if (isset( $_SESSION['muid'] )) {
    echo "<h3>Cached ID: {$_SESSION['muid']}</h3> <br/>";
}

if (empty( $_SESSION['muid'] )) {

    $auth_body = ['email' => $private_info['email'], 'password' => $private_info['password']];


    $response = $client->request('POST', 'api/session', [
        'json' => $auth_body
    ]);



    $content = json_decode($response->getBody()->getContents(), true);

    $_SESSION['muid'] = $content['id'];
}


$user_id      = $_SESSION['muid'];
$auth_headers = $headers + ['Cookie' => "metabase.SESSION_ID={$user_id}"];

/**
 * echo '<h3> Get user activity </h3> <br/>';
 *
 * $user_activity = $client->get('api/activity', [
 * 'headers' => $auth_headers,
 * ]);
 *
 * print_r(json_decode($user_activity->getBody(), true));
 * */

echo '<h3> Get info for query-bar </h3> <br/>';

$databases = $client->get('api/database', [
    'headers' => $auth_headers,
    'query'   => ['include_tables' => 'true']
]);

print_r(json_decode($databases->getBody(), true));


