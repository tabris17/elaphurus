<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Util;

use Ela\Util\Exception\InvalidArgumentException;

/**
 * 精确到毫秒的时间戳类
 */
class Timestamp
{
    /**
     * 秒数
     * 
     * @var int
     */
    protected $second;
    
    /**
     * 毫秒数
     * 
     * @var int
     */
    protected $microsecond;
    
    /**
     * 构造函数
     * 
     * @param int $second 秒数。
     * @param int $microsecond 毫秒数。
     */
    public function __construct($second = null, $microsecond = null) {
        if (empty($second) && empty($microsecond)) {
            $microtime = microtime();
            $this->second = (int)strstr($microtime, ' ');
            $this->microsecond = (int)substr($microtime, 2, 6);
            return;
        }
    
        if (isset($second)) {
            $this->second = $second;
        }
        if (isset($microsecond)) {
            $this->setMicrosecond($microsecond);
        }
    }
    
    /**
     * 获取秒数
     * 
     * @return int 返回秒数。
     */
    public function getSecond() {
        return $this->second;
    }
    
    /**
     * 设置秒数
     * 
     * @param int $second 秒数。
     * @return void
     */
    public function setSecond($second) {
        $this->second = $second;
    }
    
    /**
     * 获取时间戳的毫秒数
     * 
     * @return int 返回毫秒数。
     */
    public function getMicrosecond() {
        return $this->microsecond;
    }
    
    /**
     * 设置时间戳的毫秒数
     * 
     * @param int $microsecond 时间戳的毫秒数。
     * @return void
     */
    public function setMicrosecond($microsecond) {
        if ($microsecond >= 1000000) {
            $this->timestamp += (int)$microsecond/1000000;
            $microsecond %= 1000000;
        } elseif ($microsecond < 0) {
            throw new InvalidArgumentException('The microsecond parameter must be positive integer');
        }
        $this->microsecond = $microsecond;
    }
    
    /**
     * 比较时间戳大小
     * 
     * @param Timestamp $timestamp 时间戳。
     * @return int 返回比较结果。如果 $this 大于 $timestamp 返回 -1；两者相等返回  0；其他则返回 1。
     */
    public function compare(Timestamp $timestamp) {
        if ($this->second > $timestamp->second) {
            return -1;
        } elseif ($this->second < $timestamp->second) {
            return 1;
        } else {
            if ($this->microsecond > $timestamp->microsecond) {
                return -1;
            } elseif ($this->microsecond < $timestamp->microsecond) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    
    /**
     * 计算两个时间戳之间的间隔
     * 
     * @param Timestamp $timestamp 时间戳。
     * @param bool $positive 输出是否为负值。
     * @return Timestamp 返回时间戳之间的间隔。
     */
    public function diff(Timestamp $timestamp, &$positive = null) {
        $sign = $this->compare($timestamp);
        if ($sign <= 0) {
            $intervalOfSecond = $this->second - $timestamp->second;
            $intervalOfMicrosecond = $this->microsecond - $timestamp->microsecond;
            $positive = true;
        } else {
            $intervalOfSecond = $timestamp->second - $this->second;
            $intervalOfMicrosecond = $timestamp->microsecond - $this->microsecond;
            $positive = false;
        }
        if ($intervalOfMicrosecond < 0) {
            -- $intervalOfSecond;
            $intervalOfMicrosecond += 1000000;
        }
        return new self($intervalOfSecond, $intervalOfMicrosecond);
    }
    
    /**
     * 转化为字符串
     * 
     * @return string
     */
    public function __toString() {
        return sprintf('%u.%06u', $this->second, $this->microsecond);
    }
    
    /**
     * 格式化输出日期时间字符串
     * 
     * @param string $format 格式化字符串。
     * @return string 返回日期时间字符串。
     */
    public function format($format) {
        return str_replace('000000', sprintf('%06u', $this->microsecond), date($format, $this->second));
    }
}
