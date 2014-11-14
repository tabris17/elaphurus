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
use Ela\Di\ServiceLocator;
use Ela\Di\Di;
use Ela\Log\LoggerAwareTrait;
use Ela\Log\LoggerAwareInterface;
use Ela\Log\Logger;
use Ela\Log\Appender as LoggerAppender;
use Ela\Config\Config;
use Ela\Config\Factory as ConfigFactory;
use Ela\System;

/**
 * 应用程序类
 * 
 * 事件：begin、end、error、exception
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
    abstract protected function main();
    
    /**
     * 打印错误信息
     * 
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     */
    abstract protected function displayError($code, $message, $file, $line);
    
    /**
     * 打印异常信息
     * 
     * @param \Exception $exception
     * @return void
     */
    abstract protected function displayException($exception);
    
    /**
     * 注册应用程序异常处理句柄
     * 
     * @return \Ela\Application\AbstractApplication
     */
    public function registerExceptionHandler()
    {
        set_exception_handler([$this, 'handleException']);
        return $this;
    }
    
    /**
     * 注册应用程序错误处理句柄
     * 
     * @param int $level 处理的错误等级。
     * @return \Ela\Application\AbstractApplication
     */
    public function registerErrorHandler($level = null)
    {
        set_error_handler(
            [$this, 'handleError'],
            isset($level) ? $level : error_reporting()
        );
        return $this;
    }
    
    /**
     * 注册应用程序关闭句柄
     * 
     * @return \Ela\Application\AbstractApplication
     */
    public function registerShutdownHandler()
    {
        register_shutdown_function([$this, 'handleShutdown']);
        return $this;
    }
    
    /**
     * 构造函数
     * 
     * @param \Ela\Config\Config $config
     */
    private function __construct()
    { }
    
    /**
     * 运行应用程序
     * 
     * @return void
     */
    public function run()
    {
        $config = $this->config;
        
        $di = $this->getDi();
        if (!$di) {
            throw new \Exception($message, $code, $previous);
        }
        $di->register('Application', $this);
        $di->register('Config', $config);
        ServiceLocator::setDi($di);        
            
        $event = $this->triggerEvent('begin', null, true);
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
            
        $this->main();

        $event = $this->triggerEvent('end');
    }
    
    /**
     * 处理系统退出
     * 
     * @return void
     */
    public function handleShutdown()
    {
        if ($error = error_get_last()) {
            $this->handleError(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        }
        $this->triggerEvent('shutdown');
    }
    
    /**
     * 处理错误
     * 
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     */
    public function handleError($code, $message, $file, $line)
    {
        $event = $this->triggerEvent('error', [
            'code' => $code,
            'message' => $message,
            'file' => $file,
            'line' => $line,
        ], true);
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
        $this->displayError($code, $message, $file, $line);
    }
    
    /**
     * 处理异常
     * 
     * @param \Exception $exception
     * @return void
     */
    public function handleException($exception)
    {
        $event = $this->triggerEvent(
            'exception',
            ['exception' => $exception],
            true
        );
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
        $this->displayException($exception);
    }
    
    /**
     * 创建应用程序
     * 
     * @param string|\Ela\Config\Config|array $config 配置文件名、配置数组或者配置对象实例。
     * @return \Ela\Application\AbstractApplication
     * @throws \Ela\Application\Exception\InvalidArgumentException
     */
    public static function create($config)
    {
        if (is_array($config)) {
            $config = new Config($config);
        } elseif (is_string($config)) {
            $config = ConfigFactory::read($config);
        }
        if (!($config instanceof Config)) {
            throw new Exception\InvalidArgumentException(System::_(
                'Config should be filename, array or instance of \Ela\Config\Config; received %s',
                gettype($config)
            ));
        }
        
        $app = new static($config);
        $logger = $di->get('Logger');
        $app->setLogger($logger);
        return $app;
        /*
        $di = new Di($config->get('components'));
        
        $app->setDi($di);
        */
    }
}
