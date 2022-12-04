<?php

namespace genilto\sbackup\interface;

interface UploaderInterface
{
    /**
     * Gets the Adapter Name
     * 
     * @return string The adapter name
     */
    public function getAdapterName();

    /**
     * Starts the Authorization flow to get and save the token
     */
    public function authorizationFlow();

    /**
     * Verify if the $uploader is Authorized
     * 
     * @return bool if the uploader is Authorized
     */
    public function isAuthorized();
    
    /**
     * Upload a file
     * 
     * @param string $filesrc Source file path to be upoloaded
     * @param string $folderId Destination folder id
     * @param string $filename Filename
     * 
     * @return string file name
     * 
     * @throws Exception
     */
    public function upload( string $filesrc, string $folderId, string $filename );
}
