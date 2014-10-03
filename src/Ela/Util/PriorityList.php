<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Util;

/**
 * 有优先级的列表
 */
class PriorityList implements \Iterator, \Countable
{
    /**
     * 
     * @var \Ela\Util\PriorityListNode
     */
    protected $firstNode;
    
    /**
     * 
     * @var int
     */
    protected $count = 0;
    
    /**
     *
     * @var \Ela\Util\PriorityListNode
     */
    protected $current;
    
    /**
     * 
     * @param mixed $data
     * @param int $priority
     * @return void
     */
    public function insert($data, $priority = 0)
    {
        $node = new PriorityListNode();
        $node->data = $data;
        $node->priority = $priority;
        
        if (empty($this->firstNode)) {
            $this->count = 1;
            $this->firstNode = $node;
            return;
        }
        
        $current = $this->firstNode;
        
        if ($current->priority < $priority) {
            $node->next = $current;
            $this->firstNode = $node;
            ++ $this->count;
            return;
        }
        
        while ($current = $current->next) {
            if ($current->priority < $priority || empty($current->next)) {
                $node->next = $current->next;
                $current->next = $node;
                ++ $this->count;
                return;
            }
        }
    }

    /**
     * 
     * @param mixed $data
     * @return bool
     */
    public function remove($data)
    {
        $prev = null;
        $current = $this->firstNode;
        do {
            if ($current->data == $data) {
                if (isset($prev)) {
                    $prev->next = $current->next;
                } else {
                    $this->firstNode = $current->next;
                }
                -- $this->count;
                return true;
            }
            $prev = $current;
        } while ($current = $current->next);
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->firstNode);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Countable::count()
     */
    public function count()
    {
        return $this->count;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::current()
     */
    public function current()
    {
        return $this->current->data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::next()
     */
    public function next()
    {
        $this->current = $this->current->next;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::key()
     */
    public function key()
    {
        return $this->current->priority;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return isset($this->current);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        $this->current = $this->firstNode;
    }
}
