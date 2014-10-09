<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Layout;

/**
 * 日志布局依赖接口
 */
interface LayoutAwareInterface
{
    /**
     * 
     * @param \Ela\Log\Layout\LayoutInterface $layout
     * @return void
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * @return \Ela\Log\Layout\LayoutInterface
     */
    public function getLayout();
}
