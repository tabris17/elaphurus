<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config\Writer;

use Ela\Config\Exception\RuntimeException,
	Ela\System;

/**
 * JSON 格式配置书写器类
 */
class Json extends AbstractWriter
{
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Config\Writer\AbstractWriter::toString()
	 * @throws RuntimeException
	 */
	public function toString(array $config) {
		$json = json_encode($config);
		if ($json === false) {
			throw new RuntimeException(System::_('Error encoding JSON string: %s', Json::getLastError()));
		}
		return $json;
	}
}