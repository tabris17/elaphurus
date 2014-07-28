<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */

/**
 * 框架引导文件
 * 
 * 实现框架类自动加载。
 */

spl_autoload_register(function ($className) {
    if (substr($className, 0, 4) === 'Ela\\') {
        require __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
    }
});
