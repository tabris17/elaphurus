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
 * 邮件地址列表
 */
class AddressList implements \Countable, \Iterator
{
    /**
     * 
     * @var array
     */
    protected $addresses = array();
    
    /**
     * Constructor
     * 
     * 0 个或多个可变参数。
     * 
     * @param string|Address $address1
     * @param string|Address $address2
     * ...
     */
    public function __construct()
    {
        foreach (func_get_args() as $address) {
            if ($address instanceof Address) {
                $this->addresses[strtolower($address->getEmail())] = $address;
            } elseif (is_string($address)) {
                $this->addresses[strtolower($address)] = new Address($address);
            } else {
                throw new Exception\InvalidArgumentException(
                    System::_('Mail address must be string or instance of Ela\Mail\Address')
                );
            }
        }
    }
    
    /**
     * 向列表添加邮件地址
     *
     * @param string|Address $emailOrAddress
     * @param string $name
     * @param string $encoding
     * @return \Ela\Mail\AddressList
     */
    public function add($emailOrAddress, $name = null, $encoding = null)
    {
        if (!$emailOrAddress instanceof Address) {
            $emailOrAddress = new Address($emailOrAddress, $name, $encoding);
        }
        $email = $emailOrAddress->getEmail();
        $this->addresses[strtolower($email)] = $emailOrAddress;
        return $this;
    }
    
    /**
     * 合并两个邮件地址列表
     * 
     * @param \Ela\Mail\AddressList $addressList
     * @return \Ela\Mail\AddressList
     */
    public function merge(AddressList $addressList)
    {
        $this->addresses = array_merge($this->addresses, $addressList->addresses);
        return $this;
    }

    /**
     * 获取邮件地址对象
     * 
     * @param string $email
     * @return bool|Address
     */
    public function get($email)
    {
        $email = strtolower($email);
        if (!isset($this->addresses[$email])) {
            return false;
        }
        return $this->addresses[$email];
    }

    /**
     * 移除某个邮件地址
     * 
     * @param string $email
     * @return bool
     */
    public function remove($email)
    {
        $email = strtolower($email);
        if (!isset($this->addresses[$email])) {
            return false;
        }
        unset($this->addresses[$email]);
        return true;
    }
    
    /**
     * 是否包含某个邮件地址
     * 
     * @param string $email
     * @return bool
     */
    public function has($email)
    {
        return isset($this->addresses[strtolower($email)]);
    }
    
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->addresses);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        return reset($this->addresses);
    }
    
    public function current()
    {
        return current($this->addresses);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key()
    {
        return key($this->addresses);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next()
    {
        return next($this->addresses);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid()
    {
        $key = key($this->addresses);
        return ($key !== null && $key !== false);
    }
    
    /**
     * 转换成字符串
     * 
     * @return string
     */
    public function __toString()
    {
        return implode(";\r\n\t", $this->addresses);
    }
}
