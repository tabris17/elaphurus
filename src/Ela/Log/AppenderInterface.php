<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 日志输出器接口
 */
interface AppenderInterface
{
    /**
     * 输出日志
     * 
     * @param \Ela\Log\LogEvent $logEvent
     * @return void
     */
    public function append($logEvent);

    /**
     * 开启输出器
     * 
     * @return void
     */
    public function start();
    
    /**
     * 停止输出器
     * 
     * @return void
     */
    public function stop();
    
    /**
     * 返回输出器开启状态
     * 
     * @return bool
     */
    public function isStarted();
}
