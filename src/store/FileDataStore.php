<?php
namespace genilto\sbackup\store;

use \genilto\sbackup\store\DataStoreInterface;

class FileDataStore implements DataStoreInterface
{
    /**
     * FIle Path to Save the data
     *
     * @var string
     */
    protected $filePath;

    /**
     * Create a new FileDataStore instance
     *
     * @param string $filePath file where will be saved the data
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath . '.php';
    }

    /**
     * Get the file data
     * 
     * @return Array
     */
    private function getFileData () {
        @include ($this->filePath);
        if (!isset( $FILE_DATA_STORE_DATA )) {
            return null;
        }
        $fileData = @unserialize($FILE_DATA_STORE_DATA);
        if (isset($fileData) && is_array($fileData)) {
            
            // echo "<pre>";
            // print_r($fileData);
            // echo "</pre>";

            return $fileData;
        }
        return array();
    }

    /**
     * Get a value from the store
     *
     * @param  string $key Data Key
     *
     * @return string|null
     */
    public function get($key)
    {
        $fileData = $this->getFileData();
        if (isset($fileData[$key])) {
            return $fileData[$key];
        }
        return null;
    }

    /**
     * Set a value in the store
     * @param string $key   Data Key
     * @param string $value Data Value
     *
     * @return void
     */
    public function set($key, $value = null)
    {
        $fileData = $this->getFileData();

        if (!empty($value)) {
            $fileData[$key] = $value;
        
        } else if (isset( $fileData[$key] )) {
            unset( $fileData[$key] );
        }
        
        $serializedData = serialize($fileData);

        // Write the contents back to the file
        file_put_contents($this->filePath, '<?php $FILE_DATA_STORE_DATA=\'' . $serializedData . '\';');
    }

    /**
     * Clear the key from the store
     *
     * @param $key Data Key
     *
     * @return void
     */
    public function clear($key)
    {
        $this->set($key);
    }
}
