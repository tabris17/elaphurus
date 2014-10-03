<?php
namespace Ela\Event;

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
	 * @return boolean
	 */
	public function removeEventListener($type, $listener);
	
	/**
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function hasEventListener($type);
	
	/**
	 * 
	 * @param string $type
	 * @param mixed $params
	 * @param boolean $cancelable
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
	 * @return boolean
	 */
	public static function removeStaticEventListener($type, $listener);
	
	/**
	 *
	 * @param string $type
	 * @return boolean
	 */
	public static function hasStaticEventListener($type);
}
