<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Filter;

use Ela\Log\AppenderInterface;

/**
 * 过滤器抽象类
 */
abstract class AbstractFilter implements AppenderInterface
{
    /**
     * 日志输出器
     * 
     * @var \Ela\Log\AppenderInterface
     */
    protected $appender;
    
    /**
     * 设置被过滤的日志输出器对象
     * 
     * @param \Ela\Log\AppenderInterface $appender
     * @return null
     */
    public function setAppender(AppenderInterface $appender)
    {
        $this->appender = $appender;
    }
    
    /**
     * 获取被过滤的日志输出器对象
     *
     * @return \Ela\Log\AppenderInterface
     */
    public function getAppender()
    {
        return $this->appender;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\AppenderInterface::append()
     */
    public function append($logEvent)
    {
        if ($this->filter($logEvent)) {
            return $this->appender->append($logEvent);
        }
    }
    
    /**
     * 过滤日志事件
     * 
     * @param \Ela\Log\LogEvent $logEvent
     * @return bool 返回日志事件是否通过过滤器。
     */
    abstract public function filter($logEvent);
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\AppenderInterface::start()
     */
    public function start()
    {
        return $this->appender->start();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\AppenderInterface::stop()
     */
    public function stop()
    {
        return $this->appender->stop();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\AppenderInterface::isStarted()
     */
    public function isStarted()
    {
        return $this->appender->isStarted();
    }
}
