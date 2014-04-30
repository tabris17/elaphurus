<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail;

use Ela\System;

/**
 * 邮件地址
 */
class Address
{
	/**
	 * 
	 * @var string
	 */
	protected $email;
	
	/**
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var string
	 */
	protected $user;

	/**
	 *
	 * @var string
	 */
	protected $host;
	
	/**
	 * Constructor
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding
	 */
	public function __construct($email, $name = null, $encoding = null)
	{
		if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception\InvalidArgumentException(System::_('Invalid email address'));
		}
		$this->email = $email;
		list ($this->user, $this->host) = explode('@', $email, 2);
		if (preg_match('/[\x80-\xFF]/', $name)) {
			if (isset($encoding)) {
				$name = '=?' . $encoding . '?B?' . base64_encode($name) . '?=';
			} else {
				throw new Exception\InvalidArgumentException(System::_('Non-ASCII character in name'));
			}
		}
		$this->name = $name;
	}
	
	/**
	 * 获得邮件
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}
	
	/**
	 * 获得用户名
	 *
	 * @return string
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * 获得主机名
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * 获得名称
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * 转换成字符串
	 * 
	 * @return string
	 */
	public function __toString()
	{
		$string = "<{$this->email}>";
		$name = $this->name;
		if (empty($name)) {
			return $string;
		}
		$string = $name . ' ' . $string;
		return $string;
	}
}
