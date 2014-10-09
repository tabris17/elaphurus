<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Util;
  
/**
 * 集合类
 * 
 * 支持所有可序列化的元素类型。
 */
class Set implements \Iterator, \Countable {
    protected $elements = array();
    protected static $emptySet;
    
    /**
     * 获取空集对象
     * @return \Ela\Util\Set 返回空集对象。
     */
    public static function getEmptySet() {
        if (empty(self::$emptySet)) {
            self::$emptySet = new static();
        }
        return self::$emptySet;
    }

    /**
     * 构造函数
     * @param array $elements 集合元素。
     */
    public function __construct(array $elements = null) {
        if ($elements === null) {
            return;
        }
        foreach ($elements as $element) {
            $this->elements[$this->getKey($element)] = $element;
        }
    }
    
    /**
     * 查询集合是否为空集
     * @return bool 返回是否为空集。
     */
    public function isEmpty() {
        return empty($this->elements);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::current()
     */
    public function current() {
        return current($this->elements);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next() {
        return next($this->elements);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key() {
        //return key($this->elements);
        return;
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid() {
        return key($this->elements) !== null;
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind() {
        return reset($this->elements);
    }
    
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count() {
        return count($this->elements);
    }
    
    /**
     * 获取键值字符串
     * 
     * @param mixed $value 元素值。
     * @return string 键值字符串值。
     */
    protected function getKey($value) {
        if (is_object($value)) {
            return spl_object_hash($value);
        } else {
            return serialize($value);
        }
    }

    /**
     * 添加元素
     * 
     * @param mixed $value 元素值。
     * @return bool 返回是否执行成功。
     */
    public function insert($value) {
        $key = $this->getKey($value);
        if (key_exists($key, $this->elements)) {
            return false;
        }
        $this->elements[$key] = $value;
        return true;
    }
        
    /**
     * 移除元素
     * 
     * @param mixed $value 元素值。
     * @return bool 返回是否执行成功。
     */
    public function remove($value) {
        $key = $this->getKey($value);
        if (key_exists($key, $this->elements)) {
            unset($this->elements[$key]);
            return true;
        }
        return false;
    }
    
    /**
     * 查询元素是否存在
     * 
     * @param mixed $value 元素值。
     * @return bool 返回是否执行成功。
     */
    public function contains($value) {
        $key = $this->getKey($value);
        return key_exists($key, $this->elements);
    }
    
    /**
     * 清除所有元素
     * 
     * @return void
     */
    public function clear() {
        $this->elements = array();
    }

    /**
     * 判断一个集合是否为当前集合的子集
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return bool 返回集合是否为子集。
     */
    public function isSubset(Set $set) {
        $difference = array_diff_key($set->elements, $this->elements);
        return empty($difference);
    }
    
    /**
     * 并集运算
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return \Ela\Util\Set 返回运算结果的集合对象。
     */
    public function union(Set $set) {
        $union = new static();
        $union->elements = $set->elements + $this->elements;
        return $union;
    }
    
    /**
     * 交集运算
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return \Ela\Util\Set 返回运算结果的集合对象。
     */
    public function intersect(Set $set) {
        $intersection = new static();
        $intersection->elements = array_intersect_key($set->element, $this->elements);
        return $intersection;
    }
    
    /**
     * 补集运算
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return \Ela\Util\Set 返回运算结果的集合对象。
     */
    public function complement(Set $set) {
        $complement = new static();
        $complement->elements = array_diff_key($this->elements, $set->elements);
        return $complement;
    }
    
    /**
     * 对称差运算
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return \Ela\Util\Set 返回运算结果的集合对象。
     */
    public function symmetricDiff(Set $set) {
        $symmetricDifference = new static();
        $symmetricDifference->elements = array_diff_key($set->elements, $this->elements) + array_diff_key($this->elements, $set->elements);
        return $symmetricDifference;
    }
    
    /**
     * 判断两个集合是否相等
     * 
     * @param \Ela\Util\Set $set 集合对象。
     * @return bool 返回两个集合是否相等。
     */
    public function equals(Set $set) {
        return $this->elements == $set->elements;
    }
    
    /**
     * 获得当前集合的幂集
     * 
     * @param bool $includeEmptySet 是否包含空集。
     * @param bool $includeSelf 是否包含自身。
     * @return \Ela\Util\Set 返回当前集合的幂集对象。
     */
    public function getPowerset($includeEmptySet = false, $includeSelf = false) {
        $allElements = array_values($this->elements);
        $count = count($allElements);
        $powerset = array();
        if ($includeEmptySet) {
            $powerset[] = self::getEmptySet();
        }
        $className = get_called_class();
        $enum = function ($level, $start, $elements) use (&$enum, $allElements, $count, &$powerset, $className) {
            for ($i = $start; $i < $count - $level; $i ++) {
                $elementsCopy = $elements;
                $elementsCopy[] = $allElements[$i];
                if ($level === 0) {
                    $powerset[] = new $className($elementsCopy);
                } else {
                    $enum($level - 1, $i + 1, $elementsCopy);
                }
            }
        };
        for ($i = 0; $i < $count - 1; $i ++) {
            $enum($i, 0, array());
        }
        if ($includeSelf) {
            $powerset[] = $this;
        }
        return new static($powerset);
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        $string = '{';
        $string .= implode(',', $this->elements);
        $string .= '}';
        return $string;
    }
}
