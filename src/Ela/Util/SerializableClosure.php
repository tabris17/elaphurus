<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Util;

/**
 * 可序列化闭包类
 * 
 * 封装闭包使得其可以被序列化。
 */
class SerializableClosure
{
	/**
	 * 
	 * @var \Closure
	 */
	protected $closure;
	
	/**
	 * 
	 * @var \ReflectionFunction
	 */
	protected $reflection;
	
	/**
	 * 
	 * @var string
	 */
	protected $code;
	
	/**
	 * 
	 * @var array
	 */
	protected $usedVariables;
	
	/**
	 * Constructor
	 * 
	 * @param \Closure $closure
	 */
	public function __construct($closure)
	{
		$this->closure = $closure;
		$this->reflection = new \ReflectionFunction($closure);
		$this->code = $this->getCode();
		$this->usedVariables = $this->getUsedVariables();
	}
	
	/**
	 * 获取闭包对象
	 * 
	 * @return \Closure
	 */
	public function getClosure()
	{
		return $this->closure;
	}
	
	/**
	 * 
	 * @return mixed
	 */
	public function __invoke()
	{
		return call_user_func_array($this->closure, func_get_args());
	}

	/**
	 * 获得闭包的代码
	 * 
	 * @return string
	 */
	public function getCode()
	{
		$reflection = $this->reflection;
		$reflection->getFileName();
		$reflection->getStartLine();
		$reflection->getEndLine();
		$reflection->getParameters();
		return 'function () {}';
	}
	
	/**
	 * 获得闭包的 Upval
	 * 
	 * @return array
	 */
	public function getUsedVariables()
	{
		return array();
	}
	
	
	public function __wakeup()
	{
		extract($this->usedVariables);
		eval('$closure = '.$this->code.';');
		$this->reflection = new \ReflectionFunction($closure);
		$this->closure = $closure;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function __sleep()
	{
		return array('code', 'usedVariables');
	}
}
