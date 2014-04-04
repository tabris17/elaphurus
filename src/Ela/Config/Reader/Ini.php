<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config\Reader;

use Ela\System,
	Ela\Config\Exception\RuntimeException,
	Ela\Config\ReaderInterface;

/**
 * 读取 ini 配置文件
 * 
 * 支持 "@include" 指令来引用外部文件。
 */
class Ini implements ReaderInterface
{
	/**
	 * 文件当前路径
	 * 
	 * @var string
	 */
	protected $directory;

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Config\ReaderInterface::loadFile()
	 * @throws RuntimeException
	 */
	public function loadFile($filename) {
		if (!is_file($filename) || !is_readable($filename)) {
            throw new RuntimeException(System::_('File "%s" doesn\'t exist or not readable', $filename));
        }
        $this->directory = dirname($filename);
        set_error_handler(
            function ($error, $message = '', $file = '', $line = 0) use ($filename) {
                throw new RuntimeException(System::_('Error reading INI file "%s": %s', $filename, $message), $error);
            }, E_WARNING
        );
        $ini = parse_ini_file($filename, true);
        restore_error_handler();
        return $this->process($ini);
	}

	/**
	 * (non-PHPdoc)
	 * @see \Ela\Config\ReaderInterface::loadString()
	 * @throws RuntimeException
	 */
	public function loadString($string) {
		if (empty($string)) {
			return array();
		}
		$this->directory = null;
		set_error_handler(
			function ($error, $message = '', $file = '', $line = 0) {
				throw new RuntimeException(System::_('Error reading INI string: %s', $message), $error);
			}, E_WARNING
		);
		$ini = parse_ini_string($string, true);
		restore_error_handler();
		return $this->process($ini);
	}

	/**
	 * 处理原始数据
	 * 
	 * @param array $data 原始数据。
	 * @return array 返回处理后的数据。
	 */
	protected function process($data) {
		$config = array();
		$this->expandKey($data, $config);
		return $config;
	}

	/**
	 * 将原始键名展开成数组节点
	 *
	 * @param array $data 原始数据。
	 * @param array $config 配置数组。
	 */
	protected function expandKey($data, &$config) {
		foreach ($data as $key => $value) {
			if ($key === '@include') {
				$reader = clone $this;
				if (empty($this->directory)) {
					$includeFile = $value;
				} else {
					$includeFile = $this->directory.DIRECTORY_SEPARATOR.$value;
				}
				$included = $reader->loadFile($includeFile);
				$config = array_replace_recursive((array)$config, $included);
				continue;
			}
			$node = &$config;
			foreach (explode('.', $key) as $nodeKey) {
				$node = &$node[$nodeKey];
			}
			if (is_array($value)) {
				$this->expandKey($value, $node);
			} else {
				$node = $this->parseValue($value);
			}
		}
	}

	/**
	 * 处理原始值
	 * 
	 * 将被方括号"[]"包含的值转换成数组格式。
	 * @param string $value 需要处理的值。
	 * @return string|array 返回处理后的值。
	 */
	protected function parseValue($value) {
		if ($value{0} === '[' && $value{strlen($value) - 1} === ']') {
			return explode(',', substr($value, 1, -1));
		}
		return $value;
	}

}
