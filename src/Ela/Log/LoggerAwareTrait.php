<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 实现日志依赖接口
 */
trait LoggerAwareTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerAwareInterface::setLogger()
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerAwareInterface::getLogger()
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
