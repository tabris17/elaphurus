<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

/**
 * PHP 流日志输出器
 */
class Stream extends AbstractAppender
{
	public function __construct()
	{
		
	}
	
	public function start()
	{
		$this->isStarted = true;
	}
	
	public function stop()
	{
		$this->isStarted = false;
	}
	
	public function append($logEvent)
	{
		
	}
}
