<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Application;

use Ela\Event\EventAwareInterface;
use Ela\Event\EventAwareTrait;
use Ela\Di\DiAwareInterface;
use Ela\Di\DiAwareTrait;
use Ela\Log\LoggerAwareTrait;
use Ela\Log\LoggerAwareInterface;
use Ela\Config\Config;
use Ela\Di\ServiceLocator;

/**
 * 抽象应用程序类
 */
abstract class AbstractApplication implements
    EventAwareInterface,
    DiAwareInterface,
    LoggerAwareInterface
{
    use DiAwareTrait;
    use EventAwareTrait;
    use LoggerAwareTrait;
    
    /**
     * @return void
     */
    abstract public function main();
    
    /**
     * 
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     */
    abstract public function outputError($code, $message, $file, $line);
    
    /**
     * 
     * @param \Exception $exception
     * @return void
     */
    abstract public function outputException($exception);
    
    /**
     * 构造函数
     * 
     * @param \Ela\Config\Config $config
     */
    public function __construct(Config $config)
    {
        set_error_handler(array($this, 'handleError'), error_reporting());
        $di = ServiceLocator::getDi();
        $di->register('Application', $this);
        $di->register('Config', $config);
        $this->setDi($di);
        
    }
    
    /**
     * 
     * @return void
     */
    public function run()
    {
        try {
            $this->main();
        } catch (Exception $exception) {
            $this->handleException($exception);
        }
    }
    
    /**
     * 
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     */
    public function handleError($code, $message, $file, $line)
    {
        $event = $this->triggerEvent('error', array(
            'code' => $code,
            'message' => $message,
            'file' => $file,
            'line' => $line,
        ), true);
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
        $this->outputError($code, $message, $file, $line);
    }
    
    /**
     *
     * @param \Exception $exception
     * @return void
     */
    public function handleException($exception)
    {
        $event = $this->triggerEvent('exception', array('exception' => $exception), true);
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
        $this->outputException($exception);
    }
}
