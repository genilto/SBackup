<?php

require_once (__DIR__ . "/../vendor/autoload.php");

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;

$app = new DropboxApp(
    "x9b1g6b06ckt996", 
    "lucja21oxngiwu4", 
    "sl.BJhv_usPspI4IeseS8gO0KibmqyJTGkVUdGqjiwFrRS-fgXSLZg7a1jb4T8phK0oA3Neo5n1b-xaMeokQXJaHnm5Y0hYTjOT7uT3Pvwa9vlauxm3m6HdrfGznpscUvFkVHCcNRw");
$dropbox = new Dropbox($app);

$fileName = "apache-maven-3.8.4-bin.zip";
$pathToLocalFile = __DIR__ . "/toUpload/" . $fileName;

// Upload a file
try {
    //$dropboxFile = DropboxFile::createByPath($pathToLocalFile, DropboxFile::MODE_READ);
    $mode = DropboxFile::MODE_READ;
    $dropboxFile = new DropboxFile($pathToLocalFile, $mode);

    $fileMetadata = $dropbox->upload($dropboxFile, $fileName, ['autorename' => true]);

    // Uploaded file
    echo "<b>File: </b><br><pre>";
    print_r($fileMetadata);
    echo "</pre>";

} catch (DropboxClientException $e) {
    echo "<b>Error: </b>" . $e->getMessage();
    echo "<br><b>Details:</b> <br><pre>";
    print_r ($e);
    echo "</pre>";
}




//Get File Metadata
try {
    $fileMetadata = $dropbox->getMetadata("/license.txt");

    //File Name
    echo "<hr><b>File: </b><br><pre>";
    print_r($fileMetadata);
    echo "</pre>";
    
} catch (DropboxClientException $e) {
    echo "<b>Error: </b>" . $e->getMessage();
    echo "<br><b>Details:</b> <br><pre>";
    print_r ($e);
    echo "</pre>";
}