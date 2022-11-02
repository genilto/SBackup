<?php

interface iSBackupAdapter
{
    /**
     * Gets the Authentication URL for the adapter
     * 
     * @return string
     */
    public function getAuthUrl();
    
    /**
     * Upload a file
     * 
     * @param string $folderId
     * @param string $filename
     * @param string $filesrc
     * 
     * @return bool true for success
     */
    public function upload( string $folderId, string $filename, string $filesrc );
}
