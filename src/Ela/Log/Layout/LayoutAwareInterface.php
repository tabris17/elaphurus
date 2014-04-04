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
	 * @param LayoutInterface $layout
	 * @return mixed
	 */
	public function setLayout(LayoutInterface $layout);

	/**
	 * @return LayoutInterface
	 */
	public function getLayout();
}
