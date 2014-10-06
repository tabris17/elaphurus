<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Event;

/**
 * 事件依赖接口
 */
interface EventAwareInterface
{
    /**
     * 
     * @param \Ela\Event\EventManager $eventManager
     * @return void
     */
    public function setEventManager($eventManager);
    
    /**
     * @return \Ela\Event\EventManager
     */
    public function getEventManager();
    
    /**
     *
     * @param string $type
     * @param callback $listener
     * @param int $priority
     * @return void
     */
    public function addEventListener($type, $listener, $priority = 0);
    
    /**
     *
     * @param string $type
     * @param callback $listener
     * @return bool
     */
    public function removeEventListener($type, $listener);
    
    /**
     *
     * @param string $type
     * @return bool
     */
    public function hasEventListener($type);
    
    /**
     * 
     * @param string $type
     * @param mixed $params
     * @param bool $cancelable
     * @return \Ela\Event\Event|boolean
     */
    public function triggerEvent($type, $params = null, $cancelable = false);
    
    /**
     *
     * @param string $type
     * @param callback $listener
     * @param int $priority
     * @return void
     */
    public static function addStaticEventListener($type, $listener, $priority = 0);
    
    /**
     *
     * @param string $type
     * @param callback $listener
     * @return bool
     */
    public static function removeStaticEventListener($type, $listener);
    
    /**
     *
     * @param string $type
     * @return bool
     */
    public static function hasStaticEventListener($type);
}
