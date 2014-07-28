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
     * @param mixed $argument
     * @return string
     */
    public static function _($text, $argument = null)
    {
        if (isset($argument)) {
            $args = func_get_args();
            $args[0] = dgettext(self::VENDOR_NAME, $text);
            return call_user_func_array('sprintf', $args);
        } else {
            return dgettext(self::VENDOR_NAME, $text);
        }
    }
}
