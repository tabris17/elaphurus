<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail;

/**
 * 邮件投递接口
 */
interface TransportInterface
{
    /**
     * 
     * @param Message $mail
     * @return bool
     */
    public function send(Message $mail);
}
