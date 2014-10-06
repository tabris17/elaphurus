<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

interface ServiceInterface
{
    /**
     * 
     * @return string
     */
    public function getName();
    
    /**
     * 
     * @param string $name
     */
    public function setName($name);
    
    /**
     * 
     * @param bool $shared
     * @return void
     */
    public function setShared($shared);
    
    /**
     * 
     * @return bool
     */
    public function isShared();
    
    /**
     * @return bool
     */
    public function hasSharedInstance();
    
    /**
     * 
     * @param object $instance
     */
    public function setSharedInstance($instance);
    
    /**
     *
     * @param \Ela\Di\DiInterface $di
     * @return object
     */
    public function getSharedInstance($di);
    
    /**
     * 
     * @param \Closure $definition
     */
    public function setDefinition($definition);
    
    /**
     * @return \Closure
     */
    public function getDefinition();
    
    /**
     * 
     * @param \Ela\Di\DiInterface $di
     * @return object
     */
    public function createInstance($di);
    
    /**
     * 
     * @param \Ela\Di\DiInterface $di
     * @return object
     */
    public function getInstance($di);
}
