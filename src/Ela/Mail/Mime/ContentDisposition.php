<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Mail\Mime;

/**
 * 内容性质
 */
class ContentDisposition
{
    const ATTACHMENT = 'attachment';
    const INLINE = 'inline';
    
    public function __toString()
    {
        
    }
}
