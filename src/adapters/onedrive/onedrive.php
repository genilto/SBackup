
<?php

require_once (__DIR__ . "/vendor/autoload.php");

use Microsoft\Graph\Graph;

$tenantId = getenv('MS_GRAPH_TENTANT_ID');
$clientId = getenv('MS_GRAPH_CLIENT_ID');
$clientSecret = getenv('MS_GRAPH_CLIENT_SECRET');

$accessToken = getAuthToken ($tenantId, $clientId, $clientSecret);

echo "Access Token: " . $accessToken;

$graph = new Graph();
$graph->setAccessToken($accessToken);


// $graph->createRequest("PUT", "/users/genilto@stonebasyx.com/drive/root/children/teste.txt/content")
// 	  ->upload( __DIR__ . '/../../../html/toUpload/teste.txt' );

//$u = new UploadSession();

//POST /users/{userId}/drive/items/{itemId}/createUploadSession
    

// try {
//     $sendResult = $graph->createRequest("POST", "/users" . "/" . $this->email->getFrom()->getEmailAddress()->getAddress() . "/sendMail")
//                 ->attachBody($mailBody)
//                 ->execute();
    
//     echo "<pre>";
//     print_r($sendResult);
//     echo "</pre>";

// } catch (Exception $e) {
//     echo "<pre>";
//     print_r($e);
//     echo "</pre>";
// }

function getAuthToken ( $tenantId, $clientId, $clientSecret ) {
    $guzzle = new \GuzzleHttp\Client();
    $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
    $token = json_decode($guzzle->post($url, [
        'form_params' => [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ],
    ])->getBody()->getContents());
    return $token->access_token;
}








// $guzzle = new \GuzzleHttp\Client();
// $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/token?api-version=1.0';
// $token = json_decode($guzzle->post($url, [
//     'form_params' => [
//         'client_id' => $clientId,
//         'client_secret' => $clientSecret,
//         'resource' => 'https://graph.microsoft.com/',
//         'grant_type' => 'client_credentials',
//     ],
// ])->getBody()->getContents());
// $accessToken = $token->access_token;

// print_r ($accessToken);

die;

// {
//     "require": {
//         "kunalvarma05/dropbox-php-sdk": "^0.3.0",
//         "spatie/dropbox-api": "^1.20",
//         "microsoft/microsoft-graph": "^1.68"
//     }
// }



// /** @var Model\UploadSession $uploadSession */
// $uploadSession = $graph->createRequest("POST", "/me/drive/items/root:/doc-test2.docx:/createUploadSession")
//     ->addHeaders(["Content-Type" => "application/json"])
//     ->attachBody([
//         "item" => [
//             "@microsoft.graph.conflictBehavior" => "rename",
//             "description"    => 'File description here'
//         ]
//     ])
//     ->setReturnType(Model\UploadSession::class)
//     ->execute();

// $file = __DIR__.'./downloads/beztam.mp4';
// $handle = fopen($file, 'r');
// $fileSize = fileSize($file);
// $fileNbByte = $fileSize - 1;
// $chunkSize = 1024*1024*4;
// $fgetsLength = $chunkSize + 1;
// $start = 0;
// while (!feof($handle)) {
//     $bytes = fread($handle, $fgetsLength);
//     $end = $chunkSize + $start;
//     if ($end > $fileNbByte) {
//         $end = $fileNbByte;
//     }
//     /* or use stream
//     $stream = \GuzzleHttp\Psr7\stream_for($bytes);
//     */
//     $res = $graph->createRequest("PUT", $uploadSession->getUploadUrl())
//         ->addHeaders([
//             'Content-Length' => ($end - 1) - $start,
//             'Content-Range' => "bytes " . $start . "-" . $end . "/" . $fileSize
//         ])
//         ->setReturnType(Model\UploadSession::class)
//         ->attachBody($bytes)
//         ->execute();

//     $start = $end + 1;
// }