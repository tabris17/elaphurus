<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

/**
 * 不输出任何信息
 */
class Null extends AbstractAppender
{	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::append()
	 */
	public function append($logEvent)
	{
		return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::start()
	 */
	public function start()
	{
		$this->isStarted = true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::stop()
	 */
	public function stop()
	{
		$this->isStarted = false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::isStarted()
	 */
	public function isStarted()
	{
		return $this->isStarted;
	}

}