<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log\Layout;

use Ela\Log\Level;

/**
 * 默认行布局
 */
class Line implements LayoutInterface
{
    const DEFAULT_FORMAT = '{timestamp} {levelName}({level}): {message} [ {context} ]';
    
    /**
     * 
     * @var string
     */
    protected $formatString = self::DEFAULT_FORMAT;

    /**
     * 构造函数
     * 
     * @param string $formatString
     */
    public function __construct($formatString = null)
    {
        if (isset($formatString)) {
            $this->formatString = $formatString;
        }
    }
    
    /**
     * 格式化上下文数组
     * 
     * @param array $context
     * @return string
     */
    protected function foramtContext($context)
    {
        foreach ($context as $key => &$val) {
            $val = "$key=$val";
        }
        return implode('; ', $context);
    }

    /**
     * (non-PHPdoc)
     * @see \Ela\Log\Layout\LayoutInterface::handle()
     */
    public function handle($logEvent)
    {
        $level = $logEvent->level;
        $replace = array();
        foreach ($logEvent->context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        $message = strtr($logEvent->message, $replace);
        return strtr($this->formatString, array(
            '{timestamp}' => date('Y-m-d H:i:s', $logEvent->timestamp),
            '{levelName}' => Level::getName($level),
            '{level}' => $level,
            '{message}' => $message,
            '{context}' => $this->foramtContext($logEvent->context),
        ));
    }
}
