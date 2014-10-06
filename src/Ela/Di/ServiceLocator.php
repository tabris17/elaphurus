<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

/**
 * 服务定位器类
 */
class ServiceLocator
{
    private static $di;
    
    /**
     * @return \Ela\Di\DiInterface
     */
    public static function getDi()
    {
        if (empty(self::$di)) {
            self::$di = new Di();
        }
        return self::$di;
    }
    
    /**
     * 
     * @param \Ela\Di\DiInterface $di
     * @return void
     */
    public static function setDi(DiInterface $di)
    {
        self::$di = $di;
    }
    
    /**
     * 
     * @param string $name
     * @return object|bool
     */
    public static function get($name)
    {
        return self::getDi()->get($name);
    }
    
    /**
     * 
     * @param string $name
     * @return object|bool
     */
    public static function getSharedInstance($name)
    {
        return self::getDi()->getSharedInstance($name);
    }
    
    /**
     * 
     * @param string $name
     * @return \Ela\Di\ServiceInterface
     */
    public static function getService($name)
    {
        return self::getDi()->getService($name);
    }
    
    /**
     * @return array
     */
    public static function getServices()
    {
        return self::getDi()->getServices();
    }
    
    /**
     * 
     * @param string $name
     * @return bool
     */
    public static function has($name)
    {
        return self::getDi()->has($name);
    }
}
