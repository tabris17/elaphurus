<?php 
namespace Ela\Event;

use Ela\Util\PriorityList;
use Ela\Event\Exception\InvalidArgumentException;
use Ela\System;

class EventManager
{
    /**
     *
     * @var array
     */
    protected $listeners = array();
    
    /**
     *
     * @param \Ela\Event\Event $event
     * @param string $type
     * @return bool
    */
    public function dispatchEvent($event, $type)
    {
        if (empty($this->listeners[$type])) {
            return false;
        }
        $priorityList = $this->listeners[$type];
        foreach ($priorityList as $listener) {
            if (is_array($listener)) {
                call_user_func($listener, $event);
            } else {
                $listener($event);
            }
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return true;
    }
    
    /**
     *
     * @param string $type
     * @param callable $listener
     * @param int $priority
     * @return void
     * @throws \Ela\Event\Exceptoin\InvalidArgumentException
     */
    public function addEventListener($type, callable $listener, $priority)
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
     * @param callable $listener
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
