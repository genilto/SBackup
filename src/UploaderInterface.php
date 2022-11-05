<?php

namespace genilto\sbackup;

interface UploaderInterface
{
    /**
     * Gets the Adapter Name
     * 
     * @return string
     */
    public function getAdapterName();

    /**
     * Gets the Authentication URL for the adapter
     * 
     * @return string
     */
    public function getAuthUrl();
    
    /**
     * Upload a file
     * 
     * @param string $filesrc Source file path to be upoloaded
     * @param string $folderId Destination folder id
     * @param string $filename Filename
     * 
     * @return bool true for success
     * 
     * @throws Exception
     */
    public function upload( string $filesrc, string $folderId, string $filename );
}
