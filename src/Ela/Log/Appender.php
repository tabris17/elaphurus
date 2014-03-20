<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * ��־������������
 */
abstract class Appender
{
	/**
	 * ��һ�������
	 * 
	 * @var Appender
	 */
	protected $next;
	
	/**
	 * �����Ȩ��
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
	 * ����һ�������������
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
