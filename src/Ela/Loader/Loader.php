<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Loader;

/**
 * PSR-0 规范的类装载器
 */
class Loader
{
    const NAMESPACE_SEPARATOR = '\\';
    /**
     * 
     * @var string
     */
    protected $namespace;
    
    /**
     * 
     * @var string
     */
    protected $includePath;
    
    /**
     * 注册自动加载到系统
     * 
     * @return bool
     */
    public function register()
    {
        return spl_autoload_register([$this, 'load']);
    }
    
    /**
     * 从系统注销自动加载
     * 
     * @return bool
     */
    public function unregister()
    {
        return spl_autoload_unregister([$this, 'load']);
    }
    
    public function __construct($namespace,  $includePath)
    {
        $this->namespace = rtrim($namespace, self::NAMESPACE_SEPARATOR);
        $this->includePath = $includePath . DIRECTORY_SEPARATOR;
    }
    
    /**
     * 系统自动加载入口
     * 
     * @param string $className
     * @return null
     */
    public function load($className)
    {
        $className = ltrim($className, self::NAMESPACE_SEPARATOR);
        $fileName  = $this->includePath;
        $namespace = '';
        if ($lastNsPos = strrpos($className, self::NAMESPACE_SEPARATOR)) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName .= str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        require $fileName;
    }
}
