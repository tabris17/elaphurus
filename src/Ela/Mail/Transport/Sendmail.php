<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail\Transport;

use Ela\Mail\Mail,
	Ela\Mail\TransportInterface;

class Sendmail implements TransportInterface
{
	/**
	 * (non-PHPdoc)
	 * @see \Ela\Mail\TransportInterface::send()
	 */
	public function send(Message $mail)
	{
		//mail
	}
}
