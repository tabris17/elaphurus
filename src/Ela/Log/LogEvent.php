<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 日志事件类
 */
class LogEvent
{
    /**
     * 
     * @var string
     */
    public $message;
    
    /**
     * 
     * @var integer
     */
    public $level;
    
    /**
     * 
     * @var array
     */
    public $context;
    
    /**
     * 
     * @var \Ela\Timestamp
     */
    public $timestamp;
    
    /**
     * Constructor
     * 
     * @param integer $level
     * @param string $message
     * @param array $context
     */
    public function __construct($level, $message, $context)
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->timestamp = time();
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
    
    public function getLevelName()
    {
        return Level::getName($this->level);
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function getContext()
    {
        return $this->context;
    }
}
