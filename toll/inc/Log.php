<?php

namespace Toll_Integration;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log 
{

    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger('Toll');
    }

    public function log($message, $type = '', $data = [])
    {
        
        switch ($type) {
            case 'error':
                $this->logger->pushHandler(new StreamHandler(APP_DIR.'/log/error.log', Logger::WARNING));
                $this->logger->error($message, $data);
                break;
            case 'debug':
                $this->logger->pushHandler(new StreamHandler(APP_DIR.'/log/debug.log', Logger::DEBUG));
                $this->logger->info($message, $data);
                break;
            default:
                $this->logger->pushHandler(new StreamHandler(APP_DIR.'/log/system.log', Logger::DEBUG));
                $this->logger->info($message, $data);
                break;
        }
    }
}