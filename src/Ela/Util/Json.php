<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Util;

use Ela\System;

/**
 * 封装系统 json_encode / json_decode 函数
 */
class Json
{
    /**
     * 编码
     * 
     * @param string $value
     * @param int $options
     * @return string
     */
    public static function encode($value, $options = null)
    {
        return json_encode($value, $options);
    }
    
    /**
     * 解码
     * 
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return mixed
     */
    public static function decode($json, $assoc = null, $depth = null, $options = null)
    {
        return json_decode($json, $assoc, $depth, $options);
    }
    
    /**
     * 获得错误信息
     * 
     * @return string|null
     */
    public static function getLastError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE: return;
            case JSON_ERROR_DEPTH: return System::_('The maximum stack depth has been exceeded');
            case JSON_ERROR_STATE_MISMATCH: return System::_('Invalid or malformed JSON');
            case JSON_ERROR_CTRL_CHAR: return System::_('Control character error, possibly incorrectly encoded');
            case JSON_ERROR_SYNTAX: return System::_('Syntax error');
            case JSON_ERROR_UTF8: return System::_('Malformed UTF-8 characters, possibly incorrectly encoded');
        }
        return System::_('Unknown json decode error');
    }
}
