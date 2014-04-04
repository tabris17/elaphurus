<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

use Ela\Log\layout\LayoutAwareTrait,
	Ela\Log\Layout\LayoutAwareInterface;

/**
 * 不输出任何信息
 */
class HttpHeader extends AbstractAppender implements LayoutAwareInterface
{
	use LayoutAwareTrait;

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Appender\AbstractAppender::append()
	 */
	public function append($logEvent)
	{
		header('Log:' . $this->getLayout()->handle($logEvent));
	}
}
