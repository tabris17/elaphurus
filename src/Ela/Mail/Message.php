<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail;

use Ela\Mail\Mime\TransferEncoding;

/**
 * 邮件实体
 */
class Message
{
	/**
	 * 
	 * @var array
	 */
	protected $headers;

	/**
	 * 
	 * @var array
	 */
	protected $subject;
	
	/**
	 * 
	 * @var array
	 */
	protected $to;

	/**
	 * 
	 * @var array
	 */
	protected $from;

	/**
	 * 
	 * @var array
	 */
	protected $sender;
	
	/**
	 * 
	 * @var array
	 */
	protected $replyTo;
	
	/**
	 * 
	 * @var array
	 */
	protected $cc;
	
	/**
	 * 
	 * @var array
	 */
	protected $bcc;
	
	/**
	 * 
	 * @var string
	 */
	protected $body;
	
	/**
	 * 
	 * @var string
	 */
	protected $encoding;
	
	/**
	 * 
	 * @var TransportInterface
	 */
	protected $transport;

	/**
	 * 设置邮件默认编码
	 * 
	 * @param string $encoding
	 * @return Message
	 */
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
		return $this;
	}

	/**
	 * 获取邮件默认编码
	 * 
	 * @return string
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}
	
	/**
	 * 设置传输接口
	 * 
	 * @param TransportInterface $transport
	 * @return Message
	 */
	public function setTransport(TransportInterface $transport)
	{
		$this->transport = $transport;
		return $this;
	}
	
	/**
	 * 获得传输接口
	 * 
	 * @return TransportInterface
	 */
	public function getTransport()
	{
		$transport = $this->transport;
		if (!$transport instanceof TransportInterface) {
			$transport = $this->transport = new Transport\Sendmail();
		}
		return $transport;
	}
	
	/**
	 * 发送邮件
	 * 
	 * @return boolean
	 */
	public function send()
	{
		$transport = $this->getTransport();
		return $transport->send($this);
	}
	
	/**
	 * 设置标题
	 * 
	 * @param string $subject 邮件标题。
	 * @param string $encoding 编码。
	 * @return Message
	 */
	public function setSubject($subject, $encoding = null)
	{
		$this->subject = array($subject, $encoding);
		return $this;
	}
	
	/**
	 * 获取标题
	 * 
	 * @return string
	 */
	public function getSubject()
	{
		list($subject, $encoding) = $this->subject;
		if (empty($encoding) && (null === $encoding = $this->encoding)) {
			return $subject;
		} else {
			return '=?'.$encoding.'?B?'.base64_encode($subject).'?=';
		}
	}

	/**
	 * 获取收件人
	 * 
	 * @return string
	 */
	public function getTo()
	{
		$defaultEncoding = $this->encoding;
		return implode(',', array_map(function ($to) use ($defaultEncoding) {
			list ($email, $name, $encoding) = $to;
			if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
				return $email;
			} else {
				return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
			}
		}, $this->to));
	}
	
	/**
	 * 添加收件人
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 编码。
	 * @return Message
	 */
	public function addTo($email, $name = null, $encoding = null)
	{
		$this->to[] = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 设置邮件优先级
	 * 
	 * @param int $priority 取值	区间 [1,5]
	 * @return Message
	 */
	public function setPriority($priority)
	{
		$this->headers['X-Priority'] = $priority;
		return $this;
	}
	
	/**
	 * 获取邮件优先级
	 * 
	 * @return int|null
	 */
	public function getPriority()
	{
		return isset($this->headers['X-Priority']) ? $this->headers['X-Priority'] : null;
	}
	
	/**
	 * 添加头部
	 * 
	 * @param string $field 字段。
	 * @param string $value 值。
	 * @param boolean $overwrite 是否覆盖旧值。如果为 false 的话则追加一行。
	 * @return Message
	 */
	public function addHeader($field, $value, $overwrite = true)
	{
		if ($overwrite) {
			$this->headers[$field] = $value;
		} else {
			$oldValue = $this->headers[$field];
			if (is_array($oldValue)) {
				$this->headers[$field][] = $value;
			} else {
				$this->headers[$field] = array($oldValue, $value);
			}
		}
		return $this;
	}
	
	/**
	 * 添加多个头部
	 * 
	 * @param array $headers
	 * @param boolean $overwrite
	 * @return Message
	 */
	public function addHeaders($headers, $overwrite = true)
	{
		if ($overwrite) {
			$this->headers = array_merge($this->headers, $headers);
		} else {
			$allHeaders = $this->headers;
			foreach ($headers as $field => $value) {
				$oldValue = $allHeaders[$field];
				if (is_array($oldValue)) {
					$allHeaders[$field][] = $value;
				} else {
					$allHeaders[$field] = array($oldValue, $value);
				}
			}
			$this->headers = $allHeaders;
		}
		return $this;
	}
	
	/**
	 * 移除头部
	 * 
	 * @param string $field
	 * @return Message
	 */
	public function removeHeader($field)
	{
		unset($this->headers[$field]);
		return $this;
	}
	
	/**
	 * 获取所有头部
	 * 
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
	
	/**
	 * 清除所有头部
	 * 
	 * @return Message
	 */
	public function clearHeaders()
	{
		$this->headers = array();
		return $this;
	}
	
	/**
	 * 设置发件人
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 编码。
	 * @return Message
	 */
	public function setSender($email, $name = null, $encoding = null)
	{
		$this->sender = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 获取发件人
	 * 
	 * @return string
	 */
	public function getSender()
	{
		$defaultEncoding = $this->encoding;
		list ($email, $name, $encoding) = $this->sender;
		if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
			return $email;
		} else {
			return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
		}
	}
	
	/**
	 * 设置邮件来源
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 编码。
	 * @return Message
	 */
	public function setFrom($email, $name = null, $encoding = null)
	{
		$this->from = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 获取邮件来源
	 * 
	 * @return string
	 */
	public function getFrom()
	{
		$defaultEncoding = $this->encoding;
		list ($email, $name, $encoding) = $this->from;
		if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
			return $email;
		} else {
			return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
		}
	}
	
	/**
	 * 设置抄送
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 设置编码。
	 * @return Message
	 */
	public function addCc($email, $name = null, $encoding = null)
	{
		$this->cc[] = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 获取抄送
	 * 
	 * @return string
	 */
	public function getCc()
	{
		$defaultEncoding = $this->encoding;
		return implode(',', array_map(function ($to) use ($defaultEncoding) {
			list ($email, $name, $encoding) = $to;
			if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
				return $email;
			} else {
				return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
			}
		}, $this->cc));
	}
	
	/**
	 * 设置暗抄送
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 设置邮件默认编码。
	 * @return Message
	 */
	public function addBcc($email, $name = null, $encoding = null)
	{
		$this->bcc[] = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 获取暗抄送
	 * 
	 * @return string
	 */
	public function getBcc()
	{
		$defaultEncoding = $this->encoding;
		return implode(',', array_map(function ($to) use ($defaultEncoding) {
			list ($email, $name, $encoding) = $to;
			if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
				return $email;
			} else {
				return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
			}
		}, $this->bcc));
	}
	
	/**
	 * 设置回复人
	 * 
	 * @param string $email
	 * @param string $name
	 * @param string $encoding 编码。
	 * @return Message
	 */
	public function setReplyTo($email, $name = null, $encoding = null)
	{
		$this->replyTo = array($email, $name, $encoding);
		return $this;
	}
	
	/**
	 * 获取回复人
	 * 
	 * @return string
	 */
	public function getReplyTo()
	{
		$defaultEncoding = $this->encoding;
		list ($email, $name, $encoding) = $this->replyTo;
		if (empty($encoding) && (null === $encoding = $defaultEncoding)) {
			return $email;
		} else {
			return '=?'.$encoding.'?B?'.base64_encode($name).'?= <'.$email.'>';
		}
	}
	
	/**
	 * 设置邮件正文
	 * 
	 * @param string $body
	 * @return Message
	 */
	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}
	
	/**
	 * 获取邮件正文
	 * 
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}
}
