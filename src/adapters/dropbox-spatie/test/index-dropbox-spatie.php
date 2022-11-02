<?php

require_once (__DIR__ . "/../vendor/autoload.php");

// Incluindo a classe que criamos
require_once 'SBBackup/SBBackupDropbox.php';

// Como o processo de upload pode ser demorado, retiramos
// o limite de execução do script
set_time_limit(0);
// Dados do aplicativo no Dropbox
$token = "sl.BJhv_usPspI4IeseS8gO0KibmqyJTGkVUdGqjiwFrRS-fgXSLZg7a1jb4T8phK0oA3Neo5n1b-xaMeokQXJaHnm5Y0hYTjOT7uT3Pvwa9vlauxm3m6HdrfGznpscUvFkVHCcNRw";

// Instanciando objeto e copiando arquivos e sub-pastas da pasta 'documentos'
$backup = new SBBackupDropbox($token);

//$backup->uploadFile(__DIR__ . "/toUpload/apache-maven-3.8.4-bin.zip", "/apache-maven-3.8.4-bin.zip");
//$backup->uploadFile(__DIR__ . "/toUpload/teste.txt", "/teste.txt");
//print_r($backup->getAllEntries("/"));
//echo "Iniciando...<br>";
//$backup->uploadFolder(__DIR__ . "/toUpload/", "/");

//echo "<pre>";
//$entries = $backup->getAllEntries("/");
//print_r($entries);
//echo "</pre>";

$backup->uploadFile(__DIR__ . "/toUpload/Fotos Adilson.zip", "/fotos.zip");