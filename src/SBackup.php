<?php

class SBackup {

    /**
     * The uploader
     * 
     * @var UploaderInterface
     */
    private $uploader;

    /**
     * Logging adapter
     * 
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * Instantiate the class
     * 
     * @param UploaderInterface $uploader
     * @param LoggerInterface $logger (optional)
     */
    public function __construct ($uploader, $logger = null) {
        $this->uploader = $uploader;
        $this->logger = $logger;
    }

    /**
     * Starts the Authentication flow to get the token
     * 
     * @return string token
     */
    public function startAuthenticationFlow () {
        
    }

    /**
     * Upload a file using the uploader
     * 
     * @return bool true when success
     */
    public function upload () {
        
    }
}

?>