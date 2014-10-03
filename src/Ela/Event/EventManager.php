<?php 
namespace Ela\Event;

class EventManager extends StaticEventManager
{
	private static $staticEventManager;
	
	/**
	 * 
	 * @return \Ela\Event\StaticEventManager
	 */
	public function getStaticEventManager()
	{
		if (isset(self::$staticEventManager)) {
			return self::$staticEventManager;
		}
		return self::$staticEventManager = new StaticEventManager();
	}
}
