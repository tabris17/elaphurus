<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Util;

/**
 * 可序列化闭包类
 * 
 * 封装闭包使得其可以被序列化。
 */
class SerializableClosure
{
    /**
     * 
     * @var \Closure
     */
    protected $closure;
    
    /**
     * 
     * @var \ReflectionFunction
     */
    protected $reflection;
    
    /**
     * 
     * @var string
     */
    protected $code;
    
    /**
     * 
     * @var array
     */
    protected $usedVariables;
    
    /**
     * Constructor
     * 
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
        $this->reflection = new \ReflectionFunction($closure);
        $this->code = $this->getCode();
        $this->usedVariables = $this->getUsedVariables();
    }
    
    /**
     * 获取闭包对象
     * 
     * @return \Closure
     */
    public function getClosure()
    {
        return $this->closure;
    }
    
    /**
     * 
     * @return mixed
     */
    public function __invoke()
    {
        return call_user_func_array($this->closure, func_get_args());
    }

    /**
     * 获得闭包的代码
     * 
     * @return string
     */
    public function getCode()
    {
        $reflection = $this->reflection;
        $reflection->getEndLine();
        $reflection->getParameters();
        $file = new \SplFileObject($reflection->getFileName());
        $file->seek($reflection->getStartLine() - 1);
        $source = '';
        while ($file->key() < $reflection->getEndLine()) {
            $source .= $file->current();
            $file->next();
        }
        $tokens = token_get_all('<?php ' . $source);
        $isFunction = function ($token) {
            if (is_array($token)) {
                list ($name, $code) = $token;
                return $name === T_FUNCTION;
            }
            return false;
        };
        $isWhitespace = function ($token) {
            if (is_array($token)) {
                list ($name, $code) = $token;
                return $name === T_WHITESPACE;
            }
            return false;
        };
        $isOpenBrace = function ($token) {
            if (is_string($token)) {
                return $token === '{';
            }
            return false;
        };
        $isCloseBrace = function ($token) {
            if (is_string($token)) {
                return $token === '}';
            }
            return false;
        };
        $isOpenParen = function ($token) {
            if (is_string($token)) {
                return $token === '(';
            }
            return false;
        };
        $isCloseParen = function ($token) {
            if (is_string($token)) {
                return $token === ')';
            }
            return false;
        };

        $code = '';
        $token = current($tokens);
        do {
            if ($isFunction($token) && ($isOpenParen($nextToken = next($tokens)) || ($isWhitespace($nextToken) && $isOpenParen(next($tokens))))) {
                $code = 'function(';
                $level = 0; $begin = false;
                while ($token = next($tokens)) {
                    if ($isOpenBrace($token)) {
                        $begin = true;
                        $level += 1;
                    } elseif ($isCloseBrace($token)) {
                        $level -= 1;
                        if ($level === 0 && $begin) {
                            $code .= '}';
                            break;
                        }
                    }
                    if (is_array($token)) {
                        $code .= $token[1];
                    } else {
                        $code .= $token;
                    }
                }
                break;
            }
        } while ($token = next($tokens));
        return $code;
    }
    
    /**
     * 获得闭包的 Upval
     * 
     * @return array
     */
    public function getUsedVariables()
    {
        if (!preg_match('/^function\([^\(]*\)\s*use\s*\(([^\(]*)\)/', $this->code, $matches)) {
            return array();
        }
        $staticVariables = $this->reflection->getStaticVariables();
        $usedVariables = array();
        foreach (explode(',', $matches[1]) as $name) {
            $name = trim($name, '&$ ');
            $usedVariables[$name] = $staticVariables[$name];
        }
        return $usedVariables;
    }
    
    public function __wakeup()
    {
        extract($this->usedVariables);
        eval('$closure = '.$this->code.';');
        $this->reflection = new \ReflectionFunction($closure);
        $this->closure = $closure;
    }
    
    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        return array('code', 'usedVariables');
    }
}
