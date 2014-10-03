<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

use Ela\Log\Exception\InvalidArgumentException,
    Ela\Log\AppenderInterface;

/**
 * 集合多个输出器
 */
class Aggregate extends AbstractAppender
{
    /**
     * 
     * @var \Ela\Log\AppenderInterface
     */
    protected $appenders = array();
    
    /**
     * Constructor
     * 
     * 可以是任意多个参数。
     * 
     * @param \Ela\Log\AppenderInterface $appender1
     * @param \Ela\Log\AppenderInterface $appender2
     */
    public function __construct($appender1, $appender2)
    {
        foreach ($this->appenders = func_get_args() as $appender) {
            if (false === ($appender instanceof AppenderInterface)) {
                throw new InvalidArgumentException('');
            }
        }
    }
    
    /**
     * 添加输出器
     * 
     * @param AppenderInterface $appender
     * @return null
     */
    public function addAppender(AppenderInterface $appender)
    {
        $this->appenders[] = $appender;
    }
    
    /**
     * 移除输出器
     * 
     * @param AppenderInterface $appender
     * @return bool
     */
    public function removeAppender(AppenderInterface $appender)
    {
        foreach ($this->appenders as $key => $item) {
            if ($item === $appender) {
                unset($this->appenders[$key]);
                return true;
            }
        }
        return false;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Appender\AbstractAppender::append()
     */
    public function append($logEvent)
    {
        foreach ($this->appenders as $appender) {
            $appender->append($logEvent);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Appender\AbstractAppender::start()
     */
    public function start()
    {
        foreach ($this->appenders as $appender) {
            $appender->start();
        }
        $this->isStarted = true;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Appender\AbstractAppender::stop()
     */
    public function stop()
    {
        foreach ($this->appenders as $appender) {
            $appender->stop();
        }
        $this->isStarted = false;
    }
}
