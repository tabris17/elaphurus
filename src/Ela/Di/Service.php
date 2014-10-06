<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

/**
 * 依赖注入服务类
 */
class Service implements ServiceInterface
{
    private $shared;
    private $sharedInstance;
    private $name;
    private $definition;
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::getName()
     */
    public function getName()
    {
        return $thi->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::setName()
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::setShared()
     */
    public function setShared($shared)
    {
        $this->shared = $shared;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::isShared()
     */
    public function isShared()
    {
        return $this->shared;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::hasSharedInstance()
     */
    public function hasSharedInstance()
    {
        return isset($this->sharedInstance);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::getSharedInstance()
     */
    public function getSharedInstance($di)
    {
        if (empty($this->sharedInstance)) {
            $this->sharedInstance = $this->createInstance($di);
        }
        return $this->sharedInstance;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::setSharedInstance()
     */
    public function setSharedInstance($instance)
    {
        $this->sharedInstance = $instance;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::setDefinition()
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::getDefinition()
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::createInstance()
     */
    public function createInstance($di)
    {
        $closure = $this->definition;
        return $closure($di);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\ServiceInterface::getInstance()
     */
    public function getInstance($di)
    {   
        return $this->shared ? $this->getSharedInstance($di) : $this->createInstance($di);
    }
}
