<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

use Ela\System;

/**
 * 依赖注入类
 */
class Di implements DiInterface
{
    /**
     * 
     * @var array
     */
    protected $services = array();
    
    /**
     * 
     * @var array
     */
    protected $config = array();
    
    /**
     * 延迟注册服务
     * 从配置信息注册服务。
     * 
     * @param string $name
     * @return \Ela\Di\ServiceInterface|bool
     */
    protected function lazyRegister($name)
    {
        if ($this->config[$name]) {
            list($definition, $shared) = $this->config[$name];
            $this->services[$name] = $service = new Service();
            $service->setName($name);
            $service->setDefinition($definition);
            $service->setShared($shared);
            return $service;
        }
        return false;
    }
    
    /**
     * 是否存在服务的配置信息
     * 
     * @param string $name
     * @return bool
     */
    public function hasConfig($name)
    {
        return isset($this->config[$name]);
    }
    
    /**
     * 构造函数
     * 
     * @param array $config
     */
    public function __construct($config = null)
    {
        if (is_array($config)) {
            $this->config = $config;
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::register()
     */
    public function register($name, $definition, $shared = false)
    {
        $service = new Service();
        $service->setName($name);
        
        if ($definition instanceof \Closure) {
            $service->setShared($shared);
            $service->setDefinition($definition);
        } elseif (is_object($definition)) {
            $service->setShared(true);
            $service->setSharedInstance($definition);
        } else {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    System::_('Definition should be closure or object; received "%s"'),
                    gettype($definition)
                )
            );
        }
        $this->services[$name] = $service;
        return $service;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::getService()
     */
    public function getService($name)
    {
        $service = $this->services[$name];
        if (empty($this->services[$name]) && false === ($service = $this->lazyRegister($name))) {
            return false;
        }
        return $service;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::getServices()
     */
    public function getServices()
    {
        return $this->services;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::has()
     */
    public function has($name)
    {
        return isset($this->services[$name]);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::remove()
     */
    public function remove($name)
    {
        unset($this->services[$name]);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::get()
     */
    public function get($name)
    {
        $service = $this->getService($name);
        if (false === $service) {
            return false;
        }
        return $service->get($this);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiInterface::getSharedInstance()
     */
    public function getSharedInstance($name)
    {
        $service = $this->getService($name);
        if (false === $service) {
            return false;
        }
        return $service->getSharedInstance($this);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->register($offset, $value);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
