<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config;

use Ela\System,
	Ela\Config\ReaderInterface,
	Ela\Config\WriterInterface,
	Ela\Config\Exception\RuntimeException,
	Ela\Config\Exception\InvalidArgumentException;

/**
 * 配置对象工厂类
 */
class Factory
{
	/**
	 * 注册的配置书写器
	 * 
	 * @var array
	 */
	protected static $writers = array(
		'ini'  => 'Ela\Config\Writer\Ini',
		'json' => 'Ela\Config\Writer\Json',
		'php'  => 'Ela\Config\Writer\PhpArray',
	);

	/**
	 * 注册的配置阅读器
	 * 
	 * @var array
	 */
	protected static $readers = array(
		'ini'  => 'Ela\Config\Reader\Ini',
		'json' => 'Ela\Config\Reader\Json',
	);

	/**
	 * 注册配置读取器
	 * 
	 * @param string $extension 配置文件扩展名。
	 * @param string|ReaderInterface $reader 配置读取类对象或类名。
	 * @throws InvalidArgumentException
	 */
	public static function registerReader($extension, $reader) {
		if (is_a($reader, 'Ela\Config\ReaderInterface', true)) {
			self::$readers[$extension] = $reader;
		} else {
			throw new InvalidArgumentException(System::_('Reader should be class name or instance of Ela\Config\ReaderInterface; received "%s"',
				is_object($reader) ? get_class($reader) : $reader));
		}
	}

	/**
	 * 注册配置书写器
	 * 
	 * @param string $extension 配置文件扩展名。
	 * @param string|WriterInterface $writer 配置书写类对象或类名。
	 * @throws InvalidArgumentException
	 */
	public static function registerWriter($extension, $writer) {
		if (is_a($writer, 'Ela\Config\WriterInterface', true)) {
			self::$writers[$extension] = $reader;
		} else {
			throw new Exception(Ela\_('Writer should be class name or instance of Ela\Config\WriterInterface; received "%s"',
					is_object($writer) ? get_class($writer) : $writer));
		}
	}

	/**
	 * 从配置文件中读取配置
	 * 
	 * @param string $filename 配置文件名。
	 * @param bool $returnConfigObject 是否返回配置类对象，默认返回配置数组。
	 * @return Config|array 配置信息。
	 * @throws RuntimeException
	 */
	public static function read($filename, $returnConfigObject = false) {
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (empty($extension)) {
			throw new RuntimeException(System::_('Filename "%s" is missing an extension and cannot be auto-detected', $filename));
		}
		if ($extension === 'php') {
			if (!is_file($filename) || !is_readable($filename)) {
				throw new RuntimeException(System::_('File "%s" doesn\'t exist or not readable', $filename));
			}
			$config = include $filename;
		} elseif (empty(self::$readers[$extension])) {
			throw new RuntimeException(System::_('Unsupported config file extension: .%s', $extension));
		} else {
			$reader = self::$readers[$extension];
			if (is_string($reader)) {
				$reader = new $reader();
			}
			$config = $reader->loadFile($filename);
		}
		return ($returnConfigObject) ? $config : new Config($config);
	}

	/**
	 * 将配置写入配置文件
	 * 
	 * @param string $filename 配置文件名。
	 * @param array|Config $config 配置信息。
	 * @return null
	 * @throws RuntimeException
	 */
	public static function write($filename, $config) {
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (empty($extension)) {
			throw new RuntimeException(System::_('Filename "%s" is missing an extension and cannot be auto-detected', $filename));
		}
		if (empty(self::$writers[$extension])) {
			throw new RuntimeException(System::_('Unsupported config file extension: .%s', $extension));
		}
		$writer = self::$writers[$extension];
		if (is_string($writer)) {
			$writer = new $writer();
		}
		if ($config instanceof Config) {
			$config = $config->getConfig();
		}
		$writer->save($filename, $config);
	}
}
