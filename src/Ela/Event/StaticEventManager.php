<?php 
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Event;

class StaticEventManager extends EventManager
{   
    private static $instance;
    
    /**
     *
     * @return \Ela\Event\StaticEventManager
     */
    public static function getInstance()
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }
        return self::$instance = new self();
    }
}
