<?php 
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Event;

use Ela\Util\PriorityList;

class StaticEventManager
{
    /**
     * 
     * @var array
     */
    protected $listeners = array();

    /**
     * 
     * @param \Ela\Event\Event $event
     * @return boolean
     */
    public function dispatchEvent($event)
    {
        $type = $event->getType();
        
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
        if (empty($this->listeners[$type])) {
        	$this->listeners[$type] = $priorityList = new PriorityList();
        } else {
            $priorityList = $this->listeners[$type];
        }
        $priorityList->insert($listener, $priority);
    }
    
    /**
     *
     * @param string $type
     * @param callback $listener
     * @return boolean
     */
    public function removeEventListener($type, $listener)
    {
    	if (empty($this->listeners[$type])) {
    	    return false;
    	}
    	$priorityList = $this->listeners[$type];
    	$newQueue = new PriorityList();
    	foreach ($priorityList as $item) {
    		
    	}
    }
    
    /**
     *
     * @param string $type
     * @return boolean
     */
    public function hasEventListener($type)
    {
        return isset($this->listeners[$type]);
    }
}
