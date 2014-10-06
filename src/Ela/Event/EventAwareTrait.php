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
        $type = get_called_class() . "::$type";
        return StaticEventManager::getInstance()->addEventListener($type, $listener, $priority);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::removeStaticEventListener()
     */
    public static function removeStaticEventListener($type, $listener)
    {
        $type = get_called_class() . "::$type";
        return StaticEventManager::getInstance()->removeEventListener($type, $listener);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::hasStaticEventListener()
     */
    public static function hasStaticEventListener($type)
    {
        $type = get_called_class() . "::$type";
        return StaticEventManager::getInstance()->hasEventListener($type);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Event\EventAwareInterface::triggerEvent()
     */
    public function triggerEvent($type, $params = null, $cancelable = false)
    {
        $event = new Event($this, $type, $params, $cancelable);
        $eventManager = $this->getEventManager();
        $staticEventManager = StaticEventManager::getInstance();
        $result = $eventManager->dispatchEvent($event, $type);
        
        $className = get_class($this);
        do {
            $result = $staticEventManager->dispatchEvent($event, "$className::$type") || $result;
            if (__CLASS__ === $className) break;
        } while ($className = get_parent_class($className));
        
        if ($result) {
            return $event;
        }
        return false;
    }
}
