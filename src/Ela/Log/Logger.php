<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

/**
 * 日志记录器
 */
class Logger implements LoggerInterface
{
	/**
	 * 日志输出器接口
	 * 
	 * @var AppenderInterface
	 */
	protected $appender;
	
	/**
	 * 日志事件队列
	 * 
	 * @var \SplQueue
	 */
	protected $logEvents;
	
	/**
	 * 自动提交
	 * 
	 * @var boolean
	 */
	protected $autoCommit = true;
	
	/**
	 * 日志记录的最低级别
	 * 
	 * @var integer
	 */
	protected $level = PHP_INT_MAX;
	
	/**
	 * Construction
	 */
	public function __construct()
	{
		$this->logEvents = new \SplQueue();
		$this->appender = new Appender\Null();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::emergency()
	 */
	public function emergency($message, array $context = array())
	{
		$this->log(Level::EMERGENCY, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::alert()
	 */
	public function alert($message, array $context = array())
	{
		$this->log(Level::ALERT, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::critical()
	 */
	public function critical($message, array $context = array())
	{
		$this->log(Level::CRITICAL, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::error()
	 */
	public function error($message, array $context = array())
	{
		$this->log(Level::ERROR, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::warning()
	 */
	public function warning($message, array $context = array())
	{
		$this->log(Level::WARNING, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::notice()
	 */
	public function notice($message, array $context = array())
	{
		$this->log(Level::NOTICE, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::info()
	 */
	public function info($message, array $context = array())
	{
		$this->log(Level::INFO, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::debug()
	 */
	public function debug($message, array $context = array())
	{
		$this->log(Level::DEBUG, $message, $context);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\LoggerInterface::log()
	 */
	public function log($level, $message, array $context = array())
	{
		if ($level > $this->logLevel) return;
		
		if (is_object($message) && !method_exists($message, '__toString')) {
			throw new Exception\InvalidArgumentException('');
		}
		
		$logEvent = new LogEvent($level, $message, $context);
		$this->logEvents->enqueue($logEvent);
		if ($this->autoCommit) {
			$this->commit();
		}
	}

	/**
	 * 设置日志记录的最低级别
	 * 
	 * @param integer $level
	 * @return null
	 */
	public function setLevel($level)
	{
		$this->level = (int)$level;
	}
	
	/**
	 * 获得日志记录的最低级别
	 * 
	 * @return integer
	 */
	public function getLevel()
	{
		return $this->level;
	}
	
	/**
	 * 开启日志事务
	 */
	public function begin()
	{
		$this->autoCommit = false;
	}
	
	/**
	 * 提交日志事务
	 */
	public function commit()
	{
		$this->appender->open();
		foreach ($this->logEvents as $logEvent) {
			$this->appender->append($logEvent);
		}
		$this->appender->close();
		$this->logEvents = new \SplQueue();
	}
	
	/**
	 * 回滚日志事务
	 */
	public function rollback()
	{
		$this->logEvents = new \SplQueue();
		$this->autoCommit = true;
	}
}
