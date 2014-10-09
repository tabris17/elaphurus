<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

/**
 * 依赖注入组件依赖接口
 */
interface DiAwareInterface
{
    /**
     * 为对象设置依赖注入组件
     * 
     * @param \Ela\Di\DiInterface $di
     * @return void
     */
    public function setDi(DiInterface $di);
    
    /**
     * 返回依赖注入组件
     * 
     * @return \Ela\Di\DiInterface
     */
    public function getDi();
}
