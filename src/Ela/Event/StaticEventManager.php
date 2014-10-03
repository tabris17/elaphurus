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
     * @return bool
     */
    public function dispatchEvent($event)
    {
        $type = $event->getType();
        if (empty($this->listeners[$type])) {
            return false;
        }
        $priorityList = $this->listeners[$type];
        foreach ($priorityList as $listener) {
            
        }
        return true;
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
     * @return bool
     */
    public function removeEventListener($type, $listener)
    {
    	if (empty($this->listeners[$type])) {
    	    return false;
    	}
    	$priorityList = $this->listeners[$type];
    	return $priorityList->remove($listener);
    }
    
    /**
     *
     * @param string $type
     * @return bool
     */
    public function hasEventListener($type)
    {
        return isset($this->listeners[$type]);
    }
}
