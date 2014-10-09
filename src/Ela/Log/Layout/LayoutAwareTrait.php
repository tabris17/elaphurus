<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\layout;

use Ela\Log\Layout\Line;

/**
 * 实现日志布局依赖接口
 */
trait LayoutAwareTrait
{
    /**
     * @var LayoutInterface
     */
    protected $layout = null;
    
    /**
     * 
     * @param \Ela\Log\Layout\LayoutInterface $layout
     * @return void
     */
    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }
    
    /**
     * 
     * @return \Ela\Log\Layout\LayoutInterface
     */
    public function getLayout()
    {
        if (empty($this->layout)) {
            $this->layout = new Line();
        }
        return $this->layout;
    }
}
