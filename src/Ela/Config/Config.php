<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config;

use Ela\System,
	Ela\Config\Exception\RuntimeException;

/**
 * 配置类
 * 
 * 配置信息是一个由数组表示的树状结构。每个节点由一对键值组成。节点的值可以是一个字符串值或者一个数组。
 * 可以通过一系列用句号分隔的节点键名组成的分支名来访问树结构的某个分支节点的值。
 * 键名必须以字母开头，由大小写字母、数字及下划线组成。
 */
class Config implements \ArrayAccess
{
	/**
	 * 配置信息数组
	 * 
	 * @var array
	 */
	protected $_config;

	/**
	 * 配置信息是否为只读
	 * 
	 * @var bool
	 */
	protected $_readonly = false;

	/**
	 * Constructor
	 * 
	 * @param array $config 配置信息数组。
	 */
	public function __construct(array $config) {
		$this->_config = $config;
		$this->branch($this->_config);
	}

	/**
	 * 获取配置信息数组。
	 * 
	 * @return array 返回配置信息数组。
	 */
	public function getConfig() {
		return $this->_config;
	}

	/**
	 * 设置配置信息是否为只读
	 * 
	 * @param boolean $readonly 是否只读。
	 * @return null
	 */
	public function setReadOnly($readonly = true) {
		$this->_readonly = (bool) $readonly;
	}

	/**
	 * 获取配置信息是否为只读
	 * 
	 * @return boolean 返回配置信息是否只读。
	 */
	public function isReadOnly() {
		return $this->_readonly;
	}

	/**
	 * 重置对象配置信息成员变量
	 */
	protected function resetVars() {
		$thisVars = get_object_vars($this);
		$classVars = get_class_vars(__CLASS__);
		foreach ($classVars as $name => $var) {
			unset($thisVars[$name]);
		}
		foreach ($thisVars as $name => $var) {
			unset($this->$name);
		}
	}

	/**
	 * 合并配置信息到当前配置实例
	 * 
	 * @param array|Config $config 配置信息。
	 * @param boolean $overwrite 是否覆盖。
	 * @return boolean 返回执行是否成功。
	 */
	public function merge($config, $overwrite = true) {
		if ($config instanceof Config) {
			$config = $config->getConfig();
		} elseif (!is_array($config)) {
			return false;
		}
		$this->_config = array_replace_recursive($this->_config, $config);
		$this->resetVars();
		$this->branch($this->_config);
	}

	/**
	 * 分支处理配置信息
	 * 
	 * @param array $config 配置数组。
	 * @param string $prefix 分支前缀。
	 */
	protected function branch(&$config, $prefix = '') {
		foreach ($config as $key => &$value) {
			$branchName = $prefix.$key;
			if (is_array($value)) {
				$this->$branchName = &$value;
				$this->branch($value, $prefix.$key.'.');
			} else {
				$this->$branchName = &$value;
			}
		}
	}

	/**
	 * 获取配置信息
	 * 
	 * @param string $key 配置分支名。
	 * @return string|array 返回配置信息。
	 */
	public function get($key) {
		if (isset($this->$key)) return $this->$key;
	}

	/**
	 * 设置配置信息
	 * 
	 * @param string $key 配置分支名。
	 * @param string|array $value 配置信息。
	 * @return void
	 * @throws \Ela\Config\Exception 只读模式下抛出异常。
	 */
	public function set($key, $value) {
		// 当值为 NULL 时，做删除该分支处理
		if ($value === null) {
			$this->delete($key);
			return;
		}

		if ($this->_readonly) {
			throw new RuntimeException(System::_('Config data is readonly'));
		}
		if (isset($this->$key)) {
			$this->delete($key);
		}
		$config = &$this->_config;
		$keyChain = explode('.', $key);
		$prefix = '';
		while ($key = array_shift($keyChain)) {
			if (isset($config[$key])) {
				$config = &$config[$key];
				$prefix .= $key.'.';
			} else {
				array_unshift($keyChain, $key);
				break;
			}
		}
		$lastKey = array_pop($keyChain);
		while ($key = array_pop($keyChain)) {
			$temp = $value;
			$value = array();
			$value[$key] = $temp;
		}
		
		$config[$lastKey] = $value;
		$this->branch($config, $prefix);
	}

	/**
	 * 移除配置分支
	 * 
	 * @param array $config
	 * @param string $prefix
	 * @param string $subkey 
	 * @return null
	 */
	protected function removeBranch(&$config, $prefix, $subkey) {
		if (is_array($config[$subkey])) {
			$sub_config = &$config[$subkey];
			foreach ($sub_config as $k => &$v) {
				$this->removeBranch($sub_config, $prefix.$subkey.'.', $k);
			}
		}
		unset($config[$subkey]);
		$branch = $prefix.$subkey;
		unset($this->$branch);
	}

	/**
	 * 删除配置信息
	 * 
	 * @param string $key 配置分支名。
	 * @return boolean 返回操作是否成功。
	 * @throws \Ela\Config\Exception\RuntimeException 只读模式下抛出异常。
	 */
	public function delete($key) {
		if ($this->_readonly) {
			throw new RuntimeException(System::_('Config data is readonly'));
		}
		if (!isset($this->$key)) return false;
		$config = &$this->_config;
		$keyChain = explode('.', $key);
		$lastKey = array_pop($keyChain);
		foreach ($keyChain as $subKey) {
			$config = &$config[$subKey];
		}
		$this->removeBranch($config, implode('.', $keyChain).'.', $lastKey);
		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset) {
		return isset($this->$offset);
	}

	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetSet()
	 * @throws \Ela\Config\Exception
	 */
	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetUnset()
	 * @throws \Ela\Config\Exception
	 */
	public function offsetUnset($offset) {
		$this->delete($offset);
	}

}