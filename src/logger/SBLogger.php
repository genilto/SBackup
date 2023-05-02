<?php

namespace genilto\sbackup\logger;

use \Psr\Log\LoggerInterface;

class SBLogger {

    /**
     * Logging adapter
     * 
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * Define the log level
     * 0 - None
     * 1 - Error only
     * 2 - Warning and Error
     * 3 - Full Logging
     * @var int $logLevel
     */
    private $logLevel;

    /**
     * Instantiate the class
     * 
     * @param LoggerInterface $logger (optional)
     */
    public function __construct (LoggerInterface $logger, int $logLevel = 2) {
        $this->logger = $logger;

        if ($logLevel === 0 || $logLevel === 1 || $logLevel == 2 || $logLevel == 3) {
            $this->logLevel = $logLevel;
        }
    }

    /**
     * Log the message ocurred using the current Log Handler
     * 
     * @param string $method
     * @param string $where
     * @param string $errorMessage
     * @param array $context
     */
    private function log ($method, $where, $errorMessage, $context) {
        if ($this->logger === null) {
            return;
        }
        $error = $errorMessage;
        if (count($context) > 0) {
            $error .= ' | CONTEXT: {data}';
        }
        $this->logger->$method( "($where) $error", array("data" => $context) );
    }

    /**
     * Log the error ocurred using the current Log Handler
     * 
     * @param string $where
     * @param string $errorMessage
     * @param array $context
     */
    public function logError ($where, $errorMessage, array $context = array()) {
        if ($this->logLevel >= 1) {
            $this->log("error", $where, $errorMessage, $context );
        }
    }

    /**
     * Log the warning ocurred using the current Log Handler
     * 
     * @param string $where
     * @param string $errorMessage
     * @param array $context
     */
    public function logWarning ($where, $errorMessage, array $context = array()) {
        if ($this->logLevel >= 2) {
            $this->log("warning", $where, $errorMessage, $context );
        }
    }

    /**
     * Log the anything
     * 
     * @param string $where
     * @param string $message
     * @param array $context
     */
    public function logInfo ($where, $message, array $context = array()) {
        if ($this->logLevel >= 3) {
            $this->log("info", $where, $message, $context );
        }
    }
}

?>