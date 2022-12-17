<?php
namespace genilto\sbackup\models;

class SBackupFileMetadata
{

    /**
     * A unique identifier of the file
     *
     * @var string
     */
    protected $id;

    /**
     * The last component of the path (including extension).
     *
     * @var string
     */
    protected $name;

    /**
     * The file size in bytes.
     *
     * @var int
     */
    protected $size;

    /**
     * The full path of the file in storage service
     *
     * @var string
     */
    protected $full_path;

    /**
     * Constructor
     * 
     * @param string $id
     * @param string $name
     * @param int $size
     * @param string $full_path
     * 
     * @return SBackupFileMetadata
     */
    public function __construct( $id, $name, $size, $full_path )
    {
        $this->id = $id;
        $this->name = $name;
        $this->size = $size;
        $this->full_path = $full_path;
    }

    /**
     * Get the 'id' property of the file model.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the 'name' property of the file model.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the 'size' property of the file model.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get the 'full_path' property of the file model.
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->path_display;
    }

    /**
     * Prints the file information
     * 
     * @return string 
     */
    public function toString() {
        return "{ id: $this->id, name: $this->name, size: $this->size, full_path: $this->full_path }";
    }
}
