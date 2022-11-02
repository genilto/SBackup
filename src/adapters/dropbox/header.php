<?php

session_start();

require_once (__DIR__ . "/vendor/autoload.php");

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

//unset ($_SESSION["dropbox_access_token"]);

define("DROPBOX_CLIENT_ID", "mmadi38eulgsjkk");
define("DROPBOX_CLIENT_SECRET", "alnkn0fqx8nh5pw");

$dropboxAccessTokenObj = null;
$dropboxAccessToken = null;
if (isset($_SESSION["dropbox_access_token"])) {
    $dropboxAccessTokenObj = @unserialize($_SESSION["dropbox_access_token"]);
    if ($dropboxAccessTokenObj) {
        $dropboxAccessToken = $dropboxAccessTokenObj->getToken();
    }
}

//Configure Dropbox Application
$app = new DropboxApp(DROPBOX_CLIENT_ID, DROPBOX_CLIENT_SECRET, $dropboxAccessToken);

//Configure Dropbox service
$dropbox = new Dropbox($app);

//DropboxAuthHelper
$authHelper = $dropbox->getAuthHelper();

//Callback URL
$callbackUrl = "http://localhost:86/login-callback.php";

if ($dropboxAccessTokenObj) {

    //echo $dropboxAccessTokenObj->getExpiryTime();

    echo '<br>Refreshing access token<br>';
    $dropboxAccessTokenObj = $authHelper->getRefreshedAccessToken($dropboxAccessTokenObj);
    $dropboxAccessToken = $dropboxAccessTokenObj->getToken();
    echo '<br>New: <br>' . $dropboxAccessToken;

    //Configure Dropbox Application
    $app = new DropboxApp(DROPBOX_CLIENT_ID, DROPBOX_CLIENT_SECRET, $dropboxAccessToken);

    //Configure Dropbox service
    $dropbox = new Dropbox($app);

    //DropboxAuthHelper
    $authHelper = $dropbox->getAuthHelper();
}
