
<?php

require_once (__DIR__ . "/vendor/autoload.php");

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$tenantId = getenv('MS_GRAPH_TENTANT_ID');
$clientId = getenv('MS_GRAPH_CLIENT_ID');
$clientSecret = getenv('MS_GRAPH_CLIENT_SECRET');
$userId = "genilto@stonebasyx.com";

//$accessToken = getAuthToken ($tenantId, $clientId, $clientSecret);
$accessToken = "eyJ0eXAiOiJKV1QiLCJub25jZSI6IjNiYVBXcm1VcHNyRmdXbUhXb0h0TmJLU2ZXV0M1VGVKa0JMTzlBN2ZzbVkiLCJhbGciOiJSUzI1NiIsIng1dCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSIsImtpZCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSJ9.eyJhdWQiOiJodHRwczovL2dyYXBoLm1pY3Jvc29mdC5jb20iLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC9hMzZhNzUxOC01Mjk0LTQzZjctOGM5OS1lNTNkZWY4NmRlZjAvIiwiaWF0IjoxNjYyMzMyNzI0LCJuYmYiOjE2NjIzMzI3MjQsImV4cCI6MTY2MjMzNjYyNCwiYWlvIjoiRTJaZ1lPRE1kWDVqWDJSODUyZlcxZElONWd2NkFBPT0iLCJhcHBfZGlzcGxheW5hbWUiOiJTdG9uZUJhc3l4IEJhY2t1cHMiLCJhcHBpZCI6IjE1ODQ3ZTQ3LWU2ZDItNDcyMC1hNTllLWZiNDI3OWYyNTRjNSIsImFwcGlkYWNyIjoiMSIsImlkcCI6Imh0dHBzOi8vc3RzLndpbmRvd3MubmV0L2EzNmE3NTE4LTUyOTQtNDNmNy04Yzk5LWU1M2RlZjg2ZGVmMC8iLCJpZHR5cCI6ImFwcCIsIm9pZCI6IjE2ODQzZTUxLTY4NjctNGRlOC1hMDQyLWMyYjgxYjIwNWM1YyIsInJoIjoiMC5BWFlBR0hWcW81UlM5ME9NbWVVOTc0YmU4QU1BQUFBQUFBQUF3QUFBQUFBQUFBQ1pBQUEuIiwicm9sZXMiOlsiU2l0ZXMuUmVhZFdyaXRlLkFsbCJdLCJzdWIiOiIxNjg0M2U1MS02ODY3LTRkZTgtYTA0Mi1jMmI4MWIyMDVjNWMiLCJ0ZW5hbnRfcmVnaW9uX3Njb3BlIjoiTkEiLCJ0aWQiOiJhMzZhNzUxOC01Mjk0LTQzZjctOGM5OS1lNTNkZWY4NmRlZjAiLCJ1dGkiOiJ0c3lIcmJzMC1VU0VWcGhPMFMwcUFRIiwidmVyIjoiMS4wIiwid2lkcyI6WyIwOTk3YTFkMC0wZDFkLTRhY2ItYjQwOC1kNWNhNzMxMjFlOTAiXSwieG1zX3RjZHQiOjE2NTA0MDY3NDd9.tZ5tq4ulisDp5tl4KwFzTs8Ki2Qlu5RHsix15kPmKf6pvUK_3Q9Tb3MiMhRtoJNXxkXECyj36-YQC-90xsULFX_NAZE_LVsJMfMHQCn-ifrYFJzrIEsDOAwTAHoXtVGpBDYcRmz1voTjeqgAyEAfAxQwmihC4t7HEOCBQSLrfUbwEgqo50KgB8tLuYVikelsvDPFwBf4mGdeYdKX13hgOjgpKXj1bz3s0HEwiG4mCOCLvR-qrUuqbzqqiI978fFivpTmpMOWeurs6OoqCBtuJoL2WZoHW4lgqjCSS_RsLevR1qOyn7OVdJTMFCrXYkRMxxtQMirfpGH1eneEdVRDbQ";

echo "Access Token: " . $accessToken . "<hr>";

$graph = new Graph();
$graph->setAccessToken($accessToken);

// Upload small file / Single session
// $graph->createRequest("PUT", "/users/genilto@stonebasyx.com/drive/root/children/teste.txt/content")
//  	  ->upload( __DIR__ . '/../../../html/toUpload/teste.txt' );

$itemPath = "DJI_0002.DNG";

/** @var Model\UploadSession $uploadSession */
try {
    $uploadSession = $graph->createRequest("POST", "/users/$userId/drive/root:/$itemPath:/createUploadSession")
        ->addHeaders(["Content-Type" => "application/json"])
        // ->attachBody([
        //     "item" => [
        //         //"@microsoft.graph.conflictBehavior" => "fail", //"fail (default) | replace | rename",
        //         "description" => "A large file",
        //         // "driveItemSource" => [
        //         //     "@odata.type" => "microsoft.graph.driveItemSource" 
        //         // ],
        //         //"fileSize" => 1234,
        //         //"name" => $itemPath,
        //         // "mediaSource" => [ 
        //         //     "@odata.type" => "microsoft.graph.mediaSource" 
        //         // ]
        //     ]
        // ])
        ->setReturnType(Model\UploadSession::class)
        ->execute();
} catch (Exception $e) {
    die ( $e->getMessage() );
}

echo "<pre>";
print_r($uploadSession);
echo "</pre>";

$file = __DIR__ . '/../../../html/toUpload/DJI_0002.DNG';
$handle = fopen($file, 'r');
$fileSize = fileSize($file);
$fileNbByte = $fileSize - 1;
$chunkSize = 327680 * 3;
$fgetsLength = $chunkSize + 1;
$start = 0;

while (!feof($handle)) {
    echo "Send chunk " . $start . "<br>";
    $bytes = fread($handle, $fgetsLength);
    $end = $chunkSize + $start;
    if ($end > $fileNbByte) {
        $end = $fileNbByte;
    }
    /* or use stream
    $stream = \GuzzleHttp\Psr7\stream_for($bytes);
    */
    try {
        $res = $graph->createRequest("PUT", $uploadSession->getUploadUrl())
            ->addHeaders([
                'Content-Length' => ($end - 1) - $start,
                'Content-Range' => "bytes " . $start . "-" . $end . "/" . $fileSize
            ])
            ->setReturnType(Model\UploadSession::class)
            ->attachBody($bytes)
            ->execute();

            echo "<pre>";
            print_r($res);
            echo "</pre>";

    } catch (Exception $e) {
        die ( $e->getMessage() );
    }

    $start = $end + 1;
}

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