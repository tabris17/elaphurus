<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
 
namespace Ela\Log;

/**
 * 日志等级
 */
class Level
{
    /**
     * @const int 定义日志等级
     */
    const EMERGENCY    = 0;
    const ALERT        = 1;
    const CRITICAL    = 2;
    const ERROR        = 3;
    const WARNING    = 4;
    const NOTICE    = 5;
    const INFO        = 6;
    const DEBUG        = 7;
    const MAX        = 7;
    
    /**
     * PHP错误码和日志等级映射表
     *
     * @var array
     */
    protected static $errorLevelMap = [
        E_NOTICE            => self::NOTICE,
        E_USER_NOTICE       => self::NOTICE,
        E_WARNING           => self::WARNING,
        E_CORE_WARNING      => self::WARNING,
        E_USER_WARNING      => self::WARNING,
        E_ERROR             => self::ERROR,
        E_USER_ERROR        => self::ERROR,
        E_CORE_ERROR        => self::ERROR,
        E_RECOVERABLE_ERROR => self::ERROR,
        E_STRICT            => self::DEBUG,
        E_DEPRECATED        => self::DEBUG,
        E_USER_DEPRECATED   => self::DEBUG,
    ];
    
    /**
     * 日志等级名称表
     *
     * @var array
    */
    protected static $names = [
        self::EMERGENCY    => 'EMERGENCY',
        self::ALERT        => 'ALERT',
        self::CRITICAL    => 'CRITICAL',
        self::ERROR        => 'ERROR',
        self::WARNING    => 'WARNING',
        self::NOTICE    => 'NOTICE',
        self::INFO        => 'INFO',
        self::DEBUG        => 'DEBUG',
    ];
    
    /**
     * 获得日志等级的名称
     * 
     * @param int $level
     * @throws \Ela\Log\Exception\InvalidArgumentException
     * @return string
     */
    public static function getName($level)
    {
        if (!isset(self::$names[$level])) {
            //throw new Exception\InvalidArgumentException('');
            return 'UNKNOWN';
        }
        return self::$names[$level];
    }
    
    /**
     * 获得 PHP 错误代码对应的日志等级
     * 
     * @param int $errorCode
     * @return int
     */
    public static function getErrorLevel($errorCode)
    {
        if (!isset(self::$errorLevelMap[$errorCode])) {
            //throw new Exception\InvalidArgumentException('');
            return self::NOTICE;
        }
        return self::$errorLevelMap[$errorCode];
    }
}
