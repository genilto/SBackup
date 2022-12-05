<?php
namespace genilto\sbackup;

use \Exception;

/**
 * SBackupException
 */
class SBackupException extends Exception
{
    /**
     * @var bool ignoreRetry
     */
    private $couldRetry;

    public function __construct($message, $couldRetry = false)
    {
        parent::__construct( $message );
        $this->couldRetry = $couldRetry;
    }

    /**
     * Get if the exception indicates that it could retry the execution or not
     * 
     * @return bool if could retry
     */
    public function getCouldRetry () {
        return $this->couldRetry;
    }
}
