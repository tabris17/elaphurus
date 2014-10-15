<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail\Mime;

class ContentType
{
    /**
     * 常见类型
     * 
     * @var string
     */
    const TEXT_PLAIN = 'text/plain';
    const TEXT_HTML = 'text/html';
    const TEXT_CSS = 'text/css';
    const TEXT_CSV = 'text/csv';
    const TEXT_XML = 'text/xml';
    
    const IMAGE_GIF = 'image/gif';
    const IMAGE_JPEG = 'image/jpeg';
    const IMAGE_PNG = 'image/png';
    
    const APPLICATION_JAVASCRIPT = 'application/javascript';
    const APPLICATION_JSON = 'application/json';
    const APPLICATION_PDF = 'application/pdf';
    const APPLICATION_ZIP = 'application/zip';
    const APPLICATION_GZIP = 'application/gzip';
    const APPLICATION_OCTET_STREAM = 'application/octet-stream';
    
    const MULTIPART_MIXED = 'multipart/mixed';
    const MULTIPART_ALTERNATIVE = 'multipart/alternative';
    const MULTIPART_RELATED = 'multipart/related';
    
    /**
     * 扩展名与内容类型映射表
     * 
     * @var array
     */
    public static $extensionContentTypeMap = [
        'txt'    => self::TEXT_PLAIN,
        'html'    => self::TEXT_HTML,
        'htm'    => self::TEXT_HTML,
        'csv'    => self::TEXT_CSV,
        'css'    => self::TEXT_CSS,
        'xml'    => self::TEXT_XML,
                
        'jpeg'    => self::IMAGE_JPEG,
        'jpg'    => self::IMAGE_JPEG,
        'gif'    => self::IMAGE_GIF,
        'png'    => self::IMAGE_PNG,
            
        'js'    => self::APPLICATION_JAVASCRIPT,
        'json'    => self::APPLICATION_JSON,
        'zip'    => self::APPLICATION_ZIP,
        'gzip'    => self::APPLICATION_GZIP,
    ];

    /**
     * 获得文件对应的内容类型
     * 
     * @param string $filename
     * @return string
     */
    public static function getFileContentType($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (isset(self::$extensionContentTypeMap[$extension])) {
            return self::$extensionContentTypeMap[$extension];
        }
        return self::APPLICATION_OCTET_STREAM;
    }
    
    public function __toString()
    {
        
    }
}
