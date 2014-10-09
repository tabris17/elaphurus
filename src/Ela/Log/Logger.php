<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Log;

use Ela\Log\Exception\InvalidArgumentException;

/**
 * 日志记录器
 */
class Logger implements LoggerInterface
{
    /**
     * 日志输出器接口
     * 
     * @var \Ela\Log\AppenderInterface
     */
    protected $appender;
    
    /**
     * 日志事件队列
     * 
     * @var \SplQueue
     */
    protected $logEvents;
    
    /**
     * 自动提交
     * 
     * @var bool
     */
    protected $autoCommit = true;
    
    /**
     * 日志记录的最低级别
     * 
     * @var int
     */
    protected $level = PHP_INT_MAX;
    
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->logEvents = new \SplQueue();
        $this->appender = new Appender\Null();
    }
    
    /**
     * 设置日志输出器
     * 
     * @param \Ela\Log\AppenderInterface $appender
     * @return null
     */
    public function setAppender(AppenderInterface $appender)
    {
        $this->appender = $appender;
    }
    
    /**
     * 获取日志输出器
     * 
     * @return \Ela\Log\AppenderInterface 
     */
    public function getAppender()
    {
        return $this->appender;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::emergency()
     */
    public function emergency($message, array $context = array())
    {
        $this->log(Level::EMERGENCY, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::alert()
     */
    public function alert($message, array $context = array())
    {
        $this->log(Level::ALERT, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::critical()
     */
    public function critical($message, array $context = array())
    {
        $this->log(Level::CRITICAL, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::error()
     */
    public function error($message, array $context = array())
    {
        $this->log(Level::ERROR, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::warning()
     */
    public function warning($message, array $context = array())
    {
        $this->log(Level::WARNING, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::notice()
     */
    public function notice($message, array $context = array())
    {
        $this->log(Level::NOTICE, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::info()
     */
    public function info($message, array $context = array())
    {
        $this->log(Level::INFO, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::debug()
     */
    public function debug($message, array $context = array())
    {
        $this->log(Level::DEBUG, $message, $context);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Ela\Log\LoggerInterface::log()
     */
    public function log($level, $message, array $context = array())
    {
        if ($level > $this->level) return;
        $logEvent = new LogEvent($level, $message, $context);
        $this->logEvents->enqueue($logEvent);
        if ($this->autoCommit) {
            $this->commit();
        }
    }

    /**
     * 设置日志记录的最低级别
     * 
     * @param int $level
     * @return null
     */
    public function setLevel($level)
    {
        $this->level = (int)$level;
    }
    
    /**
     * 获得日志记录的最低级别
     * 
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * 开启日志事务
     */
    public function begin()
    {
        $this->autoCommit = false;
    }
    
    /**
     * 提交日志事务
     */
    public function commit()
    {
        $appender = $this->appender;
        $appender->start();
        foreach ($this->logEvents as $logEvent) {
            $appender->append($logEvent);
        }
        $appender->stop();
        $this->logEvents = new \SplQueue();
    }
    
    /**
     * 回滚日志事务
     */
    public function rollback()
    {
        $this->logEvents = new \SplQueue();
        $this->autoCommit = true;
    }
    
    /**
     * 注册到错误处理句柄
     * 
     * @param \Ela\Log\Logger $logger
     * @param bool $continue
     * @return mixed
     */
    public static function registerErrorHandler(Logger $logger, $continue = false)
    {
        return set_error_handler(function ($level, $message, $file, $line, $context) use ($logger, $continue) {
            if (error_reporting() & $level) {
                $logger->log(Level::getErrorLevel($level), $message, array(
                    'errno'        => $level,
                    'file'        => $file,
                    'line'        => $line,
                    'context'    => $context,
                ));
            }
            return !$continue;
        });
    }
    
    /**
     * 
     * @return bool
     */
    public static function unregisterErrorHandler()
    {
        return restore_error_handler();
    }
    
    /**
     * 
     * @param \Ela\Log\Logger $logger
     * @return mixed
     */
    public static function registerExceptionHandler(Logger $logger)
    {
        return set_exception_handler(function ($exception) use ($logger) {
            do {
                $priority = Level::ERROR;
                if ($exception instanceof ErrorException) {
                    $priority = Level::getErrorLevel($exception->getSeverity());
                }
                $context = array(
                    'file'  => $exception->getFile(),
                    'line'  => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                );
                if (isset($exception->xdebug_message)) {
                    $context['xdebug'] = $exception->xdebug_message;
                }
                $logger->log($priority, $exception->getMessage(), $context);
                $exception = $exception->getPrevious();
            } while ($exception);
        });
    }
    
    /**
     * 
     * @return bool
     */
    public static function unregisterExceptionHandler()
    {
        return restore_exception_handler();
    }
    
    /**
     * 
     * @param \Ela\Log\Logger $logger
     * @return void
     */
    public static function registeredFatalErrorHandler(Logger $logger)
    {
        register_shutdown_function(function () use ($logger) {
            $error = error_get_last();
            if (null !== $error && $error['type'] === E_ERROR) {
                $logger->log(Level::getErrorLevel(E_ERROR),
                    $error['message'],
                    array(
                        'file' => $error['file'],
                        'line' => $error['line']
                    )
                );
            }
        });
    }
}
