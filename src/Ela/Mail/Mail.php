<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail;

/**
 * 邮件实体
 */
class Mail
{
	/**
	 * 
	 * @var array
	 */
	protected $headers;
	
	/**
	 * 
	 * @var Body
	 */
	protected $body;
	
	/**
	 * 
	 * @var string
	 */
	protected $encoding = 'ASCII';

	/**
	 * 设置邮件编码
	 * 
	 * @param string $encoding
	 * @return Mail
	 */
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
		return $this;
	}
	
	/**
	 * 
	 * @param string $subject
	 * @return Mail
	 */
	public function setSubject($subject)
	{
		return $this;
	}
	
	/**
	 * 
	 * @param string $email
	 * @param string $name
	 * @return Mail
	 */
	public function setTo($email, $name = null)
	{
		return $this;
	}
	
	/**
	 * 
	 * @param string $email
	 * @param string $name
	 * @return Mail
	 */
	public function setFrom($email, $name = null)
	{
		return $this;
	}
	
	/**
	 *
	 * @param string $email
	 * @param string $name
	 * @return Mail
	 */
	public function addFrom($email, $name = null)
	{
		return $this;
	}
	
	public function setCc()
	{
		
	}
	
	public function setBcc()
	{
		
	}
	
	public function setReplyTo()
	{
		
	}
	
	public function attach($fileContent)
	{
		
	}

}
