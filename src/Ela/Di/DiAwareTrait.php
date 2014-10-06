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
    protected $di;
    
    /**
     * 
     * @param \Ela\Di\DiInterface $di
     */
    public function setDi(DiInterface $di)
    {
        $this->di = $di;
    }
    
    /**
     * @return \Ela\Di\DiInterface
     */
    public function getDi()
    {
        return $this->di;
    }
}
