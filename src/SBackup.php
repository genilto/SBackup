<?php

namespace genilto\sbackup;

use \Exception;
use \genilto\sbackup\logger\SBLogger;

use \genilto\sbackup\UploaderInterface;
use \genilto\sbackup\store\DataStoreInterface;

class SBackup {

    /**
     * @var int $MAX_UPLOAD_TRIES
     */
    public static $MAX_UPLOAD_TRIES = 3;

    /**
     * @var int $SECONDS_BETWEEN_TRIES
     */
    public static $SECONDS_BETWEEN_TRIES = 5;

    /**
     * The uploader
     * 
     * @var UploaderInterface $uploader
     */
    private $uploader;

    /**
     * Logging adapter
     * 
     * @var SBLogger $logger
     */
    private $logger;

    /**
     * Instantiate the class
     * 
     * @param UploaderInterface $uploader
     * @param DataStoreInterface $dataStore (optional)
     * @param LoggerInterface $logger (optional)
     */
    public function __construct (UploaderInterface $uploader, SBLogger $logger) {
        $this->uploader = $uploader;
        $this->logger = $logger;
    }

    /**
     * Gets the Adapter Name
     * 
     * @return string The adapter name
     */
    public function getAdapterName() {
        return $this->uploader->getAdapterName();
    }

    /**
     * Verify if the $uploader is Authorized
     * 
     * @return boolean if the uploader is Authorized
     */
    public function isAuthorized () {
        return $this->uploader->isAuthorized();
    }

    /**
     * Starts the Authorization flow to get the token
     * 
     * @return string token
     */
    public function authorizationFlow () {
        return $this->uploader->authorizationFlow();
    }

    /**
     * Upload a file using the uploader
     * 
     * @param string $filesrc Source file path to be upoloaded
     * @param string $folderId Destination folder id
     * @param string $filename Filename
     * 
     * @return SBackupFileMetadata file information
     * 
     * @throws Exception
     */
    public function upload( string $filesrc, string $folderId, string $filename, bool $deleteSourceAfterUpload = false ) {
        return $this->tryUpload( $filesrc, $folderId, $filename, $deleteSourceAfterUpload, 1 );
    }

    private function tryUpload ( string $filesrc, string $folderId, string $filename, bool $deleteSourceAfterUpload, int $tryNumber ) {
        $context = [
            '$filesrc' => $filesrc,
            '$folderId' => $folderId,
            '$filename' => $filename,
            '$deleteSourceAfterUpload' => $deleteSourceAfterUpload,
            '$tryNumber' => $tryNumber
        ];

        $this->logger->logInfo ('upload', "New Upload", $context);
        
        try {
            $uploadedFile = $this->uploader->upload( $filesrc, $folderId, $filename );
            
            $context['$uploadedFile'] = $uploadedFile;
            $this->logger->logInfo ('upload', "File uploaded successfully", $context);

            if ($deleteSourceAfterUpload) {
                if (@unlink($filesrc)) {
                    $this->logger->logInfo ('upload', "Source file deleted", $context);
                } else {
                    $this->logger->logError ('upload', "Error deleting Source file", $context);
                }
            }

            return $uploadedFile;

        } catch (SBackupException $e) {
            $this->logger->logError ('upload', $e->getMessage(), $context);

            // If reach the MAX tries, must abort the process
            if (!$e->getCouldRetry() || $tryNumber >= self::$MAX_UPLOAD_TRIES) {
                throw $e;
            }

            $secondsToWait = (self::$SECONDS_BETWEEN_TRIES * $tryNumber);
            $this->logger->logError ('upload', "Trying the upload again after " . $secondsToWait . " seconds", $context);
            @sleep( $secondsToWait );

            return $this->tryUpload( $filesrc, $folderId, $filename, $deleteSourceAfterUpload, ($tryNumber + 1) );

        } catch (Exception $e) {
            $this->logger->logError ('upload', $e->getMessage(), $context);
            throw $e;
        }
    }
}

?>