<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela;

/**
 * 框架系统类
 * 
 * 框架的核心信息和功能。
 */
class System
{
    /**
     * 库文件路径
     * 
     * @var string
     */
    const LIB_PATH = __DIR__;
    
    /**
     * 版本
     * 
     * @var string
     */
    const VERSION = '1.0.0';
    
    /**
     * 完整版本
     * 
     * @var string
     */
    const FULL_VERSION = 'Elaphurus PHP Web Framework Version 1.0.0';
    
    /**
     * 框架名称
     * 
     * @var string
     */
    const VENDOR_NAME = 'elaphurus';
    
    /**
     * 本地化字符串函数
     * 
     * @param string $text
     * @return string
     */
    public static function _($text)
    {
        return \dgettext(self::VENDOR_NAME, $text);
    }
    
    /**
     * 读写文件
     * 
     * 如果读取失败则调用回掉函数生成数据并写入文件。
     * 
     * @param string $filename 文件名。
     * @param callable $callback 回调函数参数为文件句柄，返回值为写入内容。
     * @return string|bool
     */
    public static function readAndWriteFile($filename, callable $callback)
    {
        if (file_exists($filename) && ($content = file_get_contents($filename))) {
            return $content;
        }
        $handle = fopen($filename, 'c+');
        if (false === $handle) {
            return false;
        }
        if (false === flock($handle, LOCK_EX)) {
            fclose($handle);
            return false;
        }
        if (!($filesize = filesize($filename)) ||empty($content = fread($handle, $filesize))) {
            $content = $callback($handle);
        }
        flock($handle, LOCK_UN);
        fclose($handle);
        return $content;
    }
}
