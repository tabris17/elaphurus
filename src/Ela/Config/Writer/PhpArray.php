<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

namespace Ela\Config\Writer;

/**
 * PHP 数组配置书写器类
 */
class PhpArray extends AbstractWriter
{
    /**
     * (non-PHPdoc)
     * @see \Ela\Config\Writer\AbstractWriter::toString()
     */
    protected function toString($config) {
        return '<?php return '.var_export($config, true).';';
    }
}
