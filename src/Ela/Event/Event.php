<?php
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
	 * @return boolean
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
	 * @return boolean
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
	 * @param boolean $cancelable
	 */
	public function __construct($target, $type, $params, $cancelable)
	{
		$this->target = $target;
		$this->type = $type;
		$this->params = $params;
		$this->cancelable = $cancelable;
	}
}
