<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail\Mime;

class ContentTransferEncoding
{
    /**
     * 编码类型
     * 
     * @var string
     */
    const _7BIT = '7bit';
    const _8BIT = '8bit';
    const QUOTED_PRINTABLE = 'quoted-printable';
    const BASE64 = 'base64';
    const BINARYZ = 'binary';
    
    /**
     * 编码
     * 
     * @param string $code
     * @param string $content
     * @return string
     */
    public static function encode($code, $content)
    {
        
    }
    
    /**
     * 解码
     * 
     * @param string $code
     * @param string $content
     * @return string
     */
    public static function decode($code, $content)
    {
        
    }
    
    public function __toString()
    {
        
    }
}
