<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Di;

/**
 * 
 */
trait DiAwareTrait
{
    /**
     * 
     * @var \Ela\Di\DiInterface
     */
    protected $di = null;
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiAwareInterface::setDi()
     */
    public function setDi(DiInterface $di)
    {
        $this->di = $di;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Di\DiAwareInterface::getDi()
     */
    public function getDi()
    {
        return $this->di;
    }
}
