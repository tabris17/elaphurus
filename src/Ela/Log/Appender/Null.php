<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

/**
 * 不输出任何信息
 */
class Null extends AbstractAppender
{
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Appender\AbstractAppender::append()
     */
    public function append($logEvent)
    {
        return;
    }
}
