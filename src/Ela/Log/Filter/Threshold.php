<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Filter;

/**
 * 日志记录等级的最低门槛
 */
class Threshold extends AbstractFilter
{
	private $level;
	
	/**
	 * Constructor
	 * 
	 * @param integer $level
	 */
	public function __construct($level)
	{
		$this->level = $level;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Log\Filter\AbstractFilter::filter()
	 */
	public function filter($logEvent)
	{
		return $logEvent->level <= $this->level;
	}
}
