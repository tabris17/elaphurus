<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Util;

/**
 * 有优先级的列表的节点
 */
class PriorityListNode
{
    /**
     * 
     * @var \Ela\Util\PriorityListNode
     */
    public $next;
    
    /**
     * 
     * @var int
     */
    public $priority;
    
    /**
     * 
     * @var mixed
     */
    public $data;
}
