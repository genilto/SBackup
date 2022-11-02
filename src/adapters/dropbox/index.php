<?php
require_once 'header.php';

if (!$dropboxAccessToken) {
    // Additional user provided parameters to pass in the request
    $params = [];
    // Url State - Additional User provided state data
    $urlState = null;
    // Token Access Type
    $tokenAccessType = "offline";
    // Fetch the Authorization/Login URL
    $authUrl = $authHelper->getAuthUrl($callbackUrl, $params, $urlState, $tokenAccessType);
    echo "<a href='" . $authUrl . "'>Log in with Dropbox</a>";
    return;
}


// $file = $dropbox->getMetadata("/Backups/CHANGELOG.pdf");

// //Id
// echo $file->getId();
// echo "<br>";
// //Name
// echo $file->getName();
// echo "<br>";
// //Size
// echo $file->getSize();
// echo "<br>";



// $listFolderContents = $dropbox->listFolder("/");

// //Fetch Items
// $items = $listFolderContents->getItems();

// //Fetch Cusrsor for listFolderContinue()
// $cursor = $listFolderContents->getCursor();

// //If more items are available
// $hasMoreItems = $listFolderContents->hasMoreItems();





use Kunnu\Dropbox\DropboxFile;

$filename = "DJI_0004.MOV";
//$filename = "2022-07-15_13-41-05_Z5GMEJZ701.zip";
$pathToLocalFile = __DIR__ . "/toUpload/$filename";
$mode = DropboxFile::MODE_READ;
$dropboxFile = DropboxFile::createByPath($pathToLocalFile, $mode);

$file = $dropbox->upload($dropboxFile, "/Backups/$filename");//, ['autorename' => true]);

// Uploaded File
echo $file->getName();
