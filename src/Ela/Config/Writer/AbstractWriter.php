<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config\Writer;

use Ela\System,
    Ela\Config\Exception\RuntimeException,
    Ela\Config\WriterInterface;

/**
 * 配置文件书写器抽象类
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * (non-PHPdoc)
     * @see \Ela\Config\WriterInterface::save()
     */
    public function save($filename, array $config) {
        if (empty($filename)) {
            throw new RuntimeException(System::_('No file name specified'));
        }
        set_error_handler(
            function ($error, $message = '', $file = '', $line = 0) use ($filename) {
                throw new RuntimeException(System::_('Error writing to "%s": %s', $filename, $message), $error);
            }, E_WARNING
        );
        try {
            file_put_contents($filename, $this->toString($config));
        } catch(RuntimeException $exception) {
            restore_error_handler();
            throw $exception;
        }
        restore_error_handler();
    }

    /**
     * (non-PHPdoc)
     * @see \Ela\Config\WriterInterface::toString()
     */
    abstract public function toString(array $config);
}