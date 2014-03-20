<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 日志处理器抽象类
 */
abstract class Appender
{
	/**
	 * 下一个输出器
	 * 
	 * @var Appender
	 */
	protected $next;
	
	/**
	 * 输出器权重
	 * 
	 * @var integer
	 */
	protected $priority = 0;
	
	/**
	 * 
	 * @param integer $priority
	 * @return null
	 */
	public function setPriority($priority)
	{
		$this->priority = (int)$priority;
	}
	
	/**
	 * 插入一个输出器至链表
	 * 
	 * @param Appender $appender
	 * @return Appender
	 */
	public function insert($appender)
	{
		if ($appender->priority > $this->priority) {
			$appender->next = $this;
			return $appender;
		}
		$this->next = $this->next->insert($appender);
		return $this;
	}
	
	public function write()
	{
		$this->next->write();
	}
	
	public function close()
	{
		if ($this->onClose()) {
			$this->next->close();
		}
	}
	
	public function addFilter($filter)
	{
		
	}
	
	public function addFormatter($formatter)
	{
	
	}
	
	protected function onClose();
	
	protected function onWrite();
}
