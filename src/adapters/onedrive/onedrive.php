
<?php

require_once (__DIR__ . "/vendor/autoload.php");
require_once (__DIR__ . "/test/uploader.php");

use Microsoft\Graph\Graph;
//use Microsoft\Graph\Model;

$tenantId = getenv('MS_GRAPH_TENTANT_ID');
$clientId = getenv('MS_GRAPH_CLIENT_ID');
$clientSecret = getenv('MS_GRAPH_CLIENT_SECRET');
$userId = getenv('MS_GRAPH_USER_ID');

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

$accessToken = getAuthToken ($tenantId, $clientId, $clientSecret);

echo "Access Token: " . $accessToken . "<hr>";

$graph = new Graph();
$graph->setAccessToken($accessToken);

//$fileName = "DJI_0053.DNG";
//$fileName = "small.txt";
$fileName = "DJI_0004.MOV";

$file = __DIR__ . "/../../../html/toUpload/$fileName";

$uploader = new UploaderClass($graph, "users/$userId");
try {
    $uploader->uploadLargeItem("root", "Backups/$fileName", $file);
} catch (Exception $e) {
     die ( $e->getMessage() );
}

// https://stonebasyx-my.sharepoint.com/:f:/p/genilto/En8biI1FaR5LvBdCe3dZCw0BqvI_UPtv_tduJbdM-a8PMA?e=yCTGYD



// Upload small file / Single session
// $graph->createRequest("PUT", "/users/genilto@stonebasyx.com/drive/root/children/teste.txt/content")
//  	  ->upload( __DIR__ . '/../../../html/toUpload/teste.txt' );

// $itemPath = "DJI_0002.DNG";

// /** @var Model\UploadSession $uploadSession */
// try {
//     $uploadSession = $graph->createRequest("POST", "/users/$userId/drive/root:/$itemPath:/createUploadSession")
//         ->addHeaders(["Content-Type" => "application/json"])
//         // ->attachBody([
//         //     "item" => [
//         //         //"@microsoft.graph.conflictBehavior" => "fail", //"fail (default) | replace | rename",
//         //         "description" => "A large file",
//         //         // "driveItemSource" => [
//         //         //     "@odata.type" => "microsoft.graph.driveItemSource" 
//         //         // ],
//         //         //"fileSize" => 1234,
//         //         //"name" => $itemPath,
//         //         // "mediaSource" => [ 
//         //         //     "@odata.type" => "microsoft.graph.mediaSource" 
//         //         // ]
//         //     ]
//         // ])
//         ->setReturnType(Model\UploadSession::class)
//         ->execute();
// } catch (Exception $e) {
//     die ( $e->getMessage() );
// }

// echo "<pre>";
// print_r($uploadSession);
// echo "</pre>";

// $file = __DIR__ . '/../../../html/toUpload/DJI_0002.DNG';
// $handle = fopen($file, 'r');
// $fileSize = fileSize($file);
// $fileNbByte = $fileSize - 1;
// $chunkSize = 327680 * 3;
// $fgetsLength = $chunkSize + 1;
// $start = 0;

// while (!feof($handle)) {
//     echo "Send chunk " . $start . "<br>";
//     $bytes = fread($handle, $fgetsLength);
//     $end = $chunkSize + $start;
//     if ($end > $fileNbByte) {
//         $end = $fileNbByte;
//     }
//     /* or use stream
//     $stream = \GuzzleHttp\Psr7\stream_for($bytes);
//     */
//     try {
//         $res = $graph->createRequest("PUT", $uploadSession->getUploadUrl())
//             ->addHeaders([
//                 'Content-Length' => ($end - 1) - $start,
//                 'Content-Range' => "bytes " . $start . "-" . $end . "/" . $fileSize
//             ])
//             ->setReturnType(Model\UploadSession::class)
//             ->attachBody($bytes)
//             ->execute();

//             echo "<pre>";
//             print_r($res);
//             echo "</pre>";

//     } catch (Exception $e) {
//         die ( $e->getMessage() );
//     }

//     $start = $end + 1;
// }








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