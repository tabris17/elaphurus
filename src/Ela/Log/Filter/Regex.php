<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Filter;

/**
 * 正则表达式过滤器
 *
 * 只有消息匹配正则表达式的事件才能通过过滤器。
 */
class Regex extends AbstractFilter
{
    private $regex;

    /**
     * Constructor
     *
     * @param int $level
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Filter\AbstractFilter::filter()
     */
    public function filter($logEvent)
    {
        return (bool)preg_match($this->regex, $logEvent->message);
    }
}
