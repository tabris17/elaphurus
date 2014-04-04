<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Config\Writer;

use Ela\System,
	Ela\Config\Exception\RuntimeException;

/**
 * INI 格式配置书写器类
 */
class Ini extends AbstractWriter
{
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Config\Writer\AbstractWriter::toString()
	 */
	public function toString(array $config) {
		return $this->process($config);
	}

	/**
	 * 处理配置信息数组
	 * 
	 * 将配置信息从数组转换为目标配置文件格式。
	 * @param array $config 配置信息数组。
	 * @param string $prefix 分支名前缀。
	 * @return string 配置文件字符串。
	 */
	protected function process(&$config, $prefix = '') {
		$iniString = '';
		foreach ($config as $k => &$v) {
			$branch = $prefix.$k;
			if (is_array($v)) {
				$iniString .= $this->process($v, $branch.'.');
			} else {
				$iniString .= $branch;
				$iniString .= '=';
				$iniString .= $this->escapeValue($v);
				$iniString .= PHP_EOL;
			}
		}
		return $iniString;
	}

	/**
	 * 编码值字符串
	 * 
	 * @param string $value 原始值。
	 * @return string 返回编码的值。
	 * @throws RuntimeException
	 */
	protected function escapeValue($value) {
		if (is_integer($value) || is_float($value)) {
			return $value;
		} elseif (is_bool($value)) {
			return ($value ? 'true' : 'false');
		} elseif (false === strpos($value, '"')) {
			return '"' . $value .  '"';
		} else {
			throw new RuntimeException(System::_('Value can not contain double quotes'));
		}
	}
}
