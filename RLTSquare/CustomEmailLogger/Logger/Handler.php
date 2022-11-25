<?php

declare(strict_types=1);

namespace RLTSquare\CustomEmailLogger\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Logger file that create custom log in log file name as rltsquare.log while url hits
 */
class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/rltsquare.log';
}
