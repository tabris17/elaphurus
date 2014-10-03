<?php 
namespace Ela\Event;

class StaticEventManager
{
	/**
	 *
	 * @param \Ela\Event\Event $event
	 * @return boolean
	 */
	public function dispatchEvent($event)
	{
	
	}
	
	/**
	 *
	 * @param string $type
	 * @param callback $listener
	 * @param int $priority
	 * @return void
	 */
	public function addEventListener($type, $listener, $priority)
	{
	
	}
	
	/**
	 *
	 * @param string $type
	 * @param callback $listener
	 * @return boolean
	 */
	public function removeEventListener($type, $listener)
	{
	
	}
	
	/**
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function hasEventListener($type)
	{
	
	}
}
