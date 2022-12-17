# SBackup

![SBackup](/assets/logo.png "SBackup")

A small way to generate and send backups to somewhere like onedrive or dropbox using PHP.

## Adapters

SBackup does nothing without an adapter. Bellow are the implemented adapers until now. 
You need to require one of above adapters to be able to use in your project.

## SBackup-Dropbox

Adapter to work with dropbox api.

https://github.com/genilto/sbackup-dropbox

## SBackup-Onedrive

Adapter to work with onedrive api.

https://github.com/genilto/sbackup-onedrive

## Install

To use the library you can just import to your project using composer. The very first thing is to create a folder for your project, called **sbackup-example** and then run the composer like below:

```console
composer require genilto/sbackup-dropbox
```

It will install all the dependencies you need to work with dropbox, including our SBackup library!

## How to use

Below is a simple example of how you can use the library. Be creative on you own project.
If you want, you can clone the examples from repository:
https://github.com/genilto/sbackup-example

### Test environment

To help us to easily test the application, we can use docker-composer, it's very simple to use and very fast to create an environment using the exactly php version you want.

Just be sure you have docker and docker-compose installed on your system.

Create a file named **docker-compose.yml** with the following content inside your project folder:

```yml
version:  '3.8'

services:
  apache:
    image: php:8.1-apache
    container_name: apache
    restart: unless-stopped
    ports:
      - 86:80
    environment:
      - DROPBOX_CLIENT_ID=${DROPBOX_CLIENT_ID}
      - DROPBOX_CLIENT_SECRET=${DROPBOX_CLIENT_SECRET}
    volumes:
      - .:/var/www

```

Remember to replace the **\${DROPBOX_CLIENT_ID}** and **\${DROPBOX_CLIENT_SECRET}** with your dropbox credentials.

Now create another folder called **html** and inside create a file called **index.php** with the following content:

```php
<!DOCTYPE  html>
<html  lang="pt-br">
<head>
<meta  charset="utf-8"/>
<title>SBackup tests!</title>
</head>
<body>
	<div  style="padding: 50px; text-align: center; max-width: 500px; margin: auto;">
		<h1  style="padding: 20px;">SBackup <small>Tests</small></h1>
		<div>
			<a href="auth.php">1 - Go to Authentication page</a>
		</div>
		<div>
			<a href="upload.php">2 - Go to Upload page</a>
		</div>
	</div>
</body>
</html>
```

Now you are ready to run the command bellow:

```console
docker-compose up -d
```

It will start a docker container, running an apache with php on [http://localhost:86](http://localhost:86).
If you see the **SBackup Tests!** page, you are ready to go ahead.

### Base Configuration

Next step you need to do is to instantiate all the classes that must be injected in SBackup, and then instantiate the SBackup class.
Inside the **html** folder, create a file called **backup.config.php** with the following content:

```php
<?php
require_once ( __DIR__  .  '/../vendor/autoload.php' );

use  \genilto\sbackup\SBackup;
use  \genilto\sbackup\adapters\SBackupDropbox;
use  \genilto\sbackup\store\FileDataStore;
use  \genilto\sbackup\logger\SBLogger;

use  \Analog\Analog;
use  \Analog\Logger;

// Defines the default timezone
Analog::$timezone =  'America/Sao_Paulo';
date_default_timezone_set(Analog::$timezone);

if (!function_exists('createDefaultLogger')) {

	/**
	* Creates the default logger using Analog as Logger class
	* Any other PSR-3 Logger could be used
	*
	* @return  SBLogger
	*/
	function  createDefaultSBackupLogger () {
		// Creates the Default Logger
		$logger =  new  Logger();
		// Define where to save the logs
		$currentDate =  date("Y-m-d");
		$logger->handler (__DIR__  .  "/$currentDate-sbackup.log");
		// Return a SBLogger instance
		return  new  SBLogger($logger, 3); // 3 - Full Logging
	}
}

/**
* Define the required APP information
*/
define("DROPBOX_CLIENT_ID",  getenv("DROPBOX_CLIENT_ID"));
define("DROPBOX_CLIENT_SECRET",  getenv("DROPBOX_CLIENT_SECRET"));

/**
* Instantiate all the required configuration classes
*/

// Here you can instantiate a class responsible for store our tokens
// We have implemented FileDataStore that store in simple php files
// but you can implement your own, for store in database for example, just implementing the interface \genilto\sbackup\store\DataStoreInterface
$SBDataStore = new FileDataStore(__DIR__  .  "/dropbox-config");

// The logger that will me used
$SBLogger = createDefaultSBackupLogger();

// Create the adapter class that will be used. In this example, our Dropbox adapter!
// Here we need to inform the Dropbox Client ID and Client Secret
$SBUploader = new SBackupDropbox($SBDataStore, $SBLogger, DROPBOX_CLIENT_ID, DROPBOX_CLIENT_SECRET);

// The main SBackup class that will the available to be used
$SBackup = new SBackup($SBUploader, $SBLogger);
```

***OBS:** To get the **Dropbox Client ID** and **Dropbox Client Secret** you can create your APP in [https://developer.dropbox.com](https://developer.dropbox.com)*

Now, you already have available **$SBackup** object to work with your backups!

### Authentication

Next step is to create a page that allows you to authenticate the program to your account in dropbox.
For that, you can create a new file inside **html** folder, called **auth.php**, with the following content:

```php
<?php
  
// In production, it is very important to create some kind of
// Authorization, blocking users that do not have access to authenticate with
// the cloud service, as dropbox.

// If anyone reachs this page, they could authenticate their 
// own account and then receive all your uploaded files!!

// Include the backup.config.php file so we have the $SBackup object available
require_once ( __DIR__  .  '/backup.config.php' );

?>
<!DOCTYPE  html>
<html  lang="pt-br">
<head>
<meta  charset="utf-8"/>
<title><?php  echo $SBackup->getAdapterName(); ?> - Authentication</title>
</head>
<body>
	<div  style="padding: 50px; text-align: center; max-width: 500px; margin: auto;">
		<h1  style="padding: 20px;">SBackup <small>Configuration</small></h1>
		<?php
		/**
		* Starts the authorization flow according to the adapter being used
		* 
		* @var  SBackup $SBackup
		*/
		$SBackup->authorizationFlow();
		?>
	</div>
</body>
</html>
```

If you want to test the authentication right now, you can navigate to [http://localhost:86/auth.php](http://localhost:86/auth.php) 

There you must follow the instructions.

### Upload

If you have followed all the steps bellow, now you are ready for upload files to dropbox.
For that you can create another file named **upload.php** inside the **html** folder, with the following content:

```php
<?php

require_once ( __DIR__ . '/backup.config.php' );

/**
 * Return the error description of the uploaded file
 * 
 * @param string $path
 * 
 * @return string|false The function returns the error description or false on failure.
 */
function getUploadErrorDescription ($uploadErrorCode) {
    $phpFileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );

    if (isset($phpFileUploadErrors[$uploadErrorCode])) {
        return $phpFileUploadErrors[$uploadErrorCode];
    }
    return false;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $SBackup->getAdapterName(); ?> - Upload</title>
</head>
<body>
    <div style="padding: 50px; text-align: center; max-width: 500px; margin: auto;">
        <h1 style="padding: 20px;">SBackup <small>Testing upload to Dropbox</small></h1>

<?php

if (isset($_POST["doupload"]) && $_POST["doupload"] == "YES") {
    
    // echo "<pre>";
    // print_r ($_FILES);
    // echo "</pre>";

    $filename = null;
    $filePath = null;
    if (isset($_FILES['file']) && isset($_FILES['file']['name'])) {
        $attach = $_FILES['file'];

        if (!empty($attach['name'])) {
            $errorCode = isset($attach["error"]) ? $attach["error"] : 0;
            if ($errorCode !== UPLOAD_ERR_OK) {
                echo "Error uploading file: " . getUploadErrorDescription($errorCode);
            } else {
                $filename = $attach['name'];
                $filePath = $attach['tmp_name'];
            }
        }
    }
    if (!empty($filePath)) {
        /**
         * @var genilto\sbackup\SBackup $SBackup
         */

        $year = date("Y");
        $month = date("m");
        $destinationFolder = "/backups/$year/$month/";
        try {
            /**
             * @var \genilto\sbackup\models\SBackupFileMetadata $uploadedFile
             */
            $uploadedFile = $SBackup->upload($filePath, $destinationFolder, $filename, false);
            echo "<b>Result:</b> File <b>" . $uploadedFile->getName() . "</b> uploaded to Dropbox!<br><br>";
            echo "<b>Details:</b> " . $uploadedFile->toString();

        } catch (Exception $e) {
            echo "<b>ERROR: </b>" . $e->getMessage();
        }
    } else {
        echo "File must be informed!";
    }
}

?>      <div style="padding: 20px;">
            <form name="dropbox-upload" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="doupload" value="YES">
                File: <input name="file" type="file" value="" />
                <button type="submit">Upload</button>
            </form>
        </div>
        <div style="padding: 10px;">
		    <a href="index.php">Back</a>
	    </div>
    </div>
</body>
</html>
```