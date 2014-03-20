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
	 * 
	 * @var Appender
	 */
	protected $appender;
	
	/**
	 * Construction
	 */
	public function __construct()
	{
		
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
		if (is_object($message) && !method_exists($message, '__toString')) {
			throw new Exception\InvalidArgumentException('');
		}
		$levelName = Level::getName($level);
		$log = array(
			'timestamp',
			'level'		=> $level,
			'levelName'	=> $levelName,
			'message'	=> $message,
			'context'	=> $context,
		);
	}
	
	/**
	 * 添加日志输出器
	 * 
	 * @param Appender $appender
	 * @return Appender
	 */
	public function addAppender($appender)
	{
		return $this->appender = $this->appender->insert($appender);
	}

	/**
	 * Destructor
	 * 
	 * 关闭全部日志输出器。
	 */
	public function __destruct()
	{
		try {
			$this->appender->close();
		} catch (\Exception $e) { }
	}

}
