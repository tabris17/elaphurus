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
     * 为对象设置日志记录器
     *
     * @param LoggerInterface $logger
     * @return mixed
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * 获得对象的日志记录器
     *
     * @return null|LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
