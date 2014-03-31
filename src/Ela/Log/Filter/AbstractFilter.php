<?php
namespace Ela\Log\Filter;

use Ela\Log\AppenderInterface;

abstract class AbstractFilter implements AppenderInterface
{
	/**
	 * 日志输出器
	 * 
	 * @var \Ela\Log\AppenderInterface
	 */
	protected $appender;
	
	/**
	 * 设置要过滤的日志输出器对象
	 * 
	 * @param \Ela\Log\AppenderInterface $appender
	 * @return null
	 */
	public function setAppender(AppenderInterface $appender)
	{
		$this->appender = $appender;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::append()
	 */
	public function append($logEvent)
	{
		if ($this->filter($logEvent)) {
			return $this->appender->append($logEvent);
		}
	}
	
	/**
	 * 过滤日志事件
	 * 
	 * @param \Ela\Log\LogEvent $logEvent
	 * @return boolean 返回日志事件是否通过过滤器。
	 */
	abstract public function filter($logEvent);
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::getName()
	 */
	public function getName()
	{
		return $this->appender->getName();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::getLayout()
	 */
	public function getLayout()
	{
		return $this->appender->getLayout();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::start()
	 */
	public function start()
	{
		return $this->appender->start();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::stop()
	 */
	public function stop()
	{
		return $this->appender->stop();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\AppenderInterface::isStarted()
	 */
	public function isStarted()
	{
		return $this->appender->isStarted();
	}
}
