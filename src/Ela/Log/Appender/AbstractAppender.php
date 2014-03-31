<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

use Ela\Log\AppenderInterface;

/**
 * 日志输出器抽象类
 */
abstract class AbstractAppender implements AppenderInterface
{
	/**
	 * 输出样式接口
	 * 
	 * @var \Ela\Log\LayoutInterface
	 */
	protected $layout;
	
	/**
	 * 输出器是否已经启动
	 * 
	 * @var boolean
	 */
	protected $isStarted = false;
	
	/**
	 * Constructor
	 * 
	 * @param string $name 输出器名称
	 * @param \Ela\Log\LayoutInterface $layout 输出样式接口
	 * @return null
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::getLayout()
	 */
	public function getLayout()
	{
		return $this->layout;
	}
	
	/**
	 * 安装过滤器
	 * 
	 * @param \Ela\Log\Filter\AbstractFilter $filter
	 * @return \Ela\Log\AppenderInterface 返回新的日志输出器接口。
	 */
	public function addFilter($filter)
	{
		$filter->setAppender($this);
		return $filter;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::start()
	 */
	public function start()
	{
		$this->isStarted = true;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::stop()
	 */
	public function stop()
	{
		$this->isStarted = false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::isStarted()
	 */
	public function isStarted()
	{
		return $this->isStarted;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::append()
	 */
	abstract public function append($logEvent);
}
