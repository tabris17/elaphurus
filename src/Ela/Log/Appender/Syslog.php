<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

/**
 * Syslog 日志输出器
 */
class Syslog extends AbstractAppender
{
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::append()
	 */
	public function append($logEvent)
	{
		syslog($priority, $message);
	}
}
