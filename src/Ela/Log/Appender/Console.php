<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

use Ela\Log\layout\LayoutAwareTrait,
    Ela\Log\layout\LayoutAwareInterface;

/**
 * 输出日志到控制台
 */
class Console extends AbstractAppender implements LayoutAwareInterface
{
    use LayoutAwareTrait;
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Appender\AbstractAppender::append()
     */
    public function append($logEvent)
    {
        echo $this->getLayout()->handle($logEvent), PHP_EOL;
    }
}
