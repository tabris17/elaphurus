<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config\Reader;

use Ela\Util\Json;
use Ela\Config\Exception\RuntimeException;
use Ela\Config\ReaderInterface;
use Ela\System;

/**
 * 读取 JSON 配置文件
 * 
 * 支持 "@include" 指令来引用外部文件。
 */
class Json implements ReaderInterface
{
    /**
     * 文件当前路径
     * 
     * @var string
     */
    protected $directory;

    /**
     * (non-PHPdoc)
     * @see \Ela\Config\ReaderInterface::loadFile()
     * @throws RuntimeException
     */
    public function loadFile($filename) 
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new RuntimeException(
                sprintf(System::_('File "%s" doesn\'t exist or not readable'), $filename)
            );
        }
        
        $this->directory = dirname($filename);
        
        $config = json_decode(file_get_contents($filename), true);
        if ($config === null) {
            throw new RuntimeException(
                sprintf(System::_('Error reading JSON file "%s": %s'), $filename, Json::getLastError())
            );
        }
        
        return $this->process($config);
    }

    /**
     * (non-PHPdoc)
     * @see \Ela\Config\ReaderInterface::loadString()
     * @throws RuntimeException
     */
    public function loadString($string) 
    {
        if (empty($string)) {
            return array();
        }
        
        $this->directory = null;

        $config = json_decode($string, true);
        if ($config === null) {
            throw new RuntimeException(
                sprintf(System::_('Error reading JSON string: %s'), Json::getLastError())
            );
        }
        
        return $this->process($config);
    }

    /**
     * 处理原始数据
     * 
     * @param array $config 原始数据。
     * @return array 返回处理后的数据。
     */
    protected function process(&$config) 
    {
        foreach ($config as $k => &$v) {
            if (is_array($v)) {
                $this->process($v);
            } elseif ($k === '@include') {
                $reader = clone $this;
                if (empty($this->directory)) {
                    $includeFile = $v;
                } else {
                    $includeFile = $this->directory.DIRECTORY_SEPARATOR.$v;
                }
                $included = $reader->loadFile($includeFile);
                $config = array_replace_recursive((array)$config, $included);
                unset($config[$k]);
            }
        }
        return $config;
    }
}
