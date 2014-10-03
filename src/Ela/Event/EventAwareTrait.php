<?php
namespace Ela\Event;

trait EventAwareTrait
{
    protected $eventManager;
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::setEventManager()
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::getEventManager()
     */
    public function getEventManager()
    {
        if (isset($this->eventManager)) {
            return $this->eventManager;
        }
        return $this->eventManager = new EventManager();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::addEventListener()
     */
    public function addEventListener($type, $listener, $priority = 0)
    {
        return $this->getEventManager()->addEventListener($type, $listener, $priority);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::removeEventListener()
     */
    public function removeEventListener($type, $listener)
    {
        return $this->getEventManager()->removeEventListener($type, $listener);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::hasEventListener()
     */
    public function hasEventListener($type)
    {
        return $this->getEventManager()->hasEventListener($type);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::addStaticEventListener()
     */
    public static function addStaticEventListener($type, $listener, $priority = 0)
    {
        return $this->getEventManager()->getStaticEventManager()->addEventListener($type, $listener, $priority);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::removeStaticEventListener()
     */
    public static function removeStaticEventListener($type, $listener)
    {
        return $this->getEventManager()->getStaticEventManager()->removeEventListener($type, $listener);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::hasStaticEventListener()
     */
    public static function hasStaticEventListener($type)
    {
        return $this->getEventManager()->getStaticEventManager()->hasEventListener($type);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::triggerEvent()
     */
    public function triggerEvent($type, $params = null, $cancelable = false)
    {
        $event = new Event($this, $type, $params, $cancelable);
        if (!$this->getEventManager()->dispatchEvent($event)) {
            return false;
        }
        return $event;
    }
}
