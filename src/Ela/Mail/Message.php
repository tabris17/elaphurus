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
	 * 设置邮件正文
	 * 
	 * @param string $body
	 * @param string $encoding
	 * @return Message
	 */
	public function setBody($body, $encoding = null)
	{
		return $this;
	}
	
}
