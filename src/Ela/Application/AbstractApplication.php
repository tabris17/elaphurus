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
use Ela\Config\Factory as ConfigFactory;
use Ela\Di\ServiceLocator;
use Ela\Log\Logger;
use Ela\Log\Appender as LoggerAppender;
use Ela\Di\Di;

/**
 * 抽象应用程序类
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
     * 
     * @var \Ela\Config\Config
     */
    protected $config;
    
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
     * 构造函数
     * 
     * @param \Ela\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    
    /**
     * 获取应用程序配置
     * 
     * @return \Ela\Config\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * 获取默认日志记录器
     * 
     * @return \Ela\Log\Logger
     */
    protected function getDefaultLogger()
    {
        $logger = new Logger();
        $logger->setAppender(new LoggerAppender\Null());
        return $logger;
    }
    
    /**
     * 获取默认配置
     * 
     * @return \Ela\Config\Config
     */
    protected function getDefaultConfig()
    {
        return new Config(array(
            
        ));
    }
    
    protected function getDefaultDi()
    {
        return new Di(array(
            
        ));
    }
    
    /**
     * 运行应用程序
     * 
     * @return void
     */
    public function run()
    {
        try {
            $config = $this->config;
            
            $di = $this->getDi();
            $di->register('Application', $this);
            $di->register('Config', $config);
            $this->di = $di;
            ServiceLocator::setDi($di);
            
            if ($logger = $di->get('Logger')) {
                $this->logger = $logger;
            } else {
                $di->register('Logger', $this->getDefaultLogger());
            }
            
            set_error_handler(array($this, 'handleError'), error_reporting());
            register_shutdown_function(array($this, 'handleShutdown'));
            
            $event = $this->triggerEvent('begin', null, true);
            if ($event && $event->isDefaultPrevented()) {
                return;
            }
            
            $this->main();
            
            $event = $this->triggerEvent('end');
        } catch (Exception $exception) {
            $this->handleException($exception);
        }
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
        $event = $this->triggerEvent('error', array(
            'code' => $code,
            'message' => $message,
            'file' => $file,
            'line' => $line,
        ), true);
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
        $event = $this->triggerEvent('exception', array('exception' => $exception), true);
        if ($event && $event->isDefaultPrevented()) {
            return;
        }
        $this->displayException($exception);
    }
    
    /**
     * 创建应用程序
     * 
     * @param string $configFile 配置文件。
     * @return \Ela\Application\AbstractApplication
     */
    public static function create($configFile)
    {
        $config = ConfigFactory::read($configFile);
        return new static($config);
    }
}
