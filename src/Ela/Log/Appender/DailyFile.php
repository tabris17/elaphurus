<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Appender;

/**
 * 按日写入文件
 */
class DailyFile extends Stream
{

	/**
	 * Constructor
	 * 
	 * @param string $path
	 */
	public function __construct($path)
	{
		$filename = $path . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
		parent::__construct($filename);
	}
}
