<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Layout;

/**
 * 日志布局接口
 */
interface LayoutInterface
{
    /**
     * 处理 LogEvent 对象
     * 
     * @param \Ela\Log\LogEvent $logEvent
     * @return string
     */
    public function handle($logEvent);
}
