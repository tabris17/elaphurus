<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Event;

class Event
{
    protected $target;
    protected $type;
    protected $cancelable;
    protected $params;
    
    private $canceled = false;
    private $stopped = false;
    
    /**
     * 
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * 
     * @return object
     */
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return void
     */
    public function preventDefault()
    {
        if ($this->cancelable) {
            $this->canceled = true;
        }
    }
    
    /**
     * 
     * @return bool
     */
    public function isDefaultPrevented()
    {
        return $this->canceled;
    }
    
    /**
     * @return void
     */
    public function stopPropagation()
    {
        $this->stopped = true;
    }
    
    /**
     * 
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->stopped;
    }
    
    /**
     * 
     * @param object $target
     * @param string $type
     * @param mixed $params
     * @param bool $cancelable
     */
    public function __construct($target, $type, $params, $cancelable)
    {
        $this->target = $target;
        $this->type = $type;
        $this->params = $params;
        $this->cancelable = $cancelable;
    }
}
