<?php

require_once 'header.php';

if (isset($_GET['code']) && isset($_GET['state'])) {
    
    //Bad practice! No input sanitization!
    $code = $_GET['code'];
    $state = $_GET['state'];

    // Fetch the AccessToken
    $accessToken = $authHelper->getAccessToken($code, $state, $callbackUrl);
    $_SESSION["dropbox_access_token"] = serialize($accessToken);

    echo "Token: " . $accessToken->getToken();
    ?>
    <br>
    <a href="index.php">Voltar</a>
    <?php   
}
