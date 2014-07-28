<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config;

/**
 * 配置阅读器接口
 */
interface ReaderInterface
{
    /**
     * 载入配置文件
     * 
     * @param string $filename 配置文件名。
     * @return array 返回配置信息。
     */
    public function loadFile($filename);

    /**
     * 载入配置文件格式的字符串
     * 
     * @param string $string 配置信息字符串。
     * @return array 返回配置信息。
     */
    public function loadString($string);
}