<?php

namespace genilto\sbackup;

use \Exception;
use \genilto\sbackup\logger\SBLogger;

use \genilto\sbackup\interface\UploaderInterface;
use \genilto\sbackup\store\DataStoreInterface;

class SBackup {

    /**
     * The uploader
     * 
     * @var UploaderInterface
     */
    private UploaderInterface $uploader;

    /**
     * Logging adapter
     * 
     * @var SBLogger
     */
    private SBLogger $logger;

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
     * @return bool true for success
     * 
     * @throws Exception
     */
    public function upload( string $filesrc, string $folderId, string $filename, bool $deleteSourceAfterUpload = false ) {
        $context = [
            '$filesrc' => $filesrc,
            '$folderId' => $folderId,
            '$filename' => $filename,
            '$deleteSourceAfterUpload' => $deleteSourceAfterUpload
        ];

        $this->logger->logInfo ('upload', "New Upload", $context);
        
        try {
            $uploadedFilename = $this->uploader->upload( $filesrc, $folderId, $filename );
            
            $context['$uploadedFilename'] = $uploadedFilename;
            $this->logger->logInfo ('upload', "File uploaded successfully", $context);

            if ($deleteSourceAfterUpload) {
                if (@unlink($filesrc)) {
                    $this->logger->logInfo ('upload', "Source file deleted", $context);
                } else {
                    $this->logger->logError ('upload', "Error deleting Source file", $context);
                }
            }
        } catch (Exception $e) {
            $this->logger->logError ('upload', $e->getMessage(), $context);
            throw $e;
        }
    }
}

?>