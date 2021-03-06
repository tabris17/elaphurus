<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 日志依赖接口
 */
interface LoggerAwareInterface
{
    /**
     * 为对象设置日志记录器
     *
     * @param \Ela\Log\LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
    
    /**
     * 获取日志记录器对象
     * 
     * @return \Ela\Log\LoggerInterface
     */
    public function getLogger();
}
