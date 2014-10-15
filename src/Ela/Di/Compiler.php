<?php
/**
 * Elaphurus Framework
 *
 * @link      https://github.com/tabris17/elaphurus
 * @license   Public Domain (http://en.wikipedia.org/wiki/Public_domain)
 */
namespace Ela\Di;

use Ela\Di\Exception\ConfigException;
use Ela\System;
/**
 * 配置编译器
 * 
 * 将数组格式的配置信息转换成可执行脚本。
 */
class Compiler
{
    public static $dependences = [
        '\Ela\Log\LoggerAwareInterface' => 'logger',
        '\Ela\Log\DiAwareInterface' => 'di',
    ];
    
    /**
     * 
     * @param string $interface 接口名。
     * @param string $service 服务名。
     * @return void
     */
    public static function registerDependence($interface, $service)
    {
        self::$dependences[$interface] = $service;
    }
    
    /**
     * 
     * @param string $interface
     * @return void
     */
    public static function unregisterDependence($interface)
    {
        unset(self::$dependences[$interface]);
    }
    
    private function getDependenceSettingCode($className, $instanceName, $diName)
    {
        $code = '';
        foreach (self::$dependences as $interface => $service) {
            if (is_subclass_of($className, $interface)) {
                $setter = 'set'.ucfirst($service);
                $code .= "{$instanceName}->{$setter}(";
                $code .= "{$diName}->get('{$service}'));\r\n";
            }
        }
        return $code;
    }
    
    /**
     * 通过配置信息编译成可执行源代码
     * 
     * <code>
     * $demo = [
     *      'class'=>'asd',
     *      'shared' => true,
     *      'params' => [
     *          'str_param1' => 'value1',
     *          'service1' => '@logger',
     *          'str_param2' => '@@logger',
     *      ],
     *      'properties' => [],
     *      'setters' => [],
     *      'methods' => [],
     * ];
     * </code>
     * @return string
     */
    public function compile(array $config)
    {
        if (empty($config['class'])) {
            throw new Exception\ConfigException(System::_('Missing class name'));
        }
        $className = $config['class'];
        if (!class_exists($className, true)) {
            throw new Exception\ConfigException(sprintf(
                System::_('Class name "%s" does not exist'),
                $className
            ));
        }
        $body = "\$instance = new $className(";
        
        $constructorParams = array();
        $reflectionClass = new \ReflectionClass($className);
        $reflectionParameters = $reflectionClass->getConstructor()->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();
            if (isset($config['params'][$parameterName])) {
                $parameterValue = $config['params'][$parameterName];
                if (is_string($parameterValue) && $parameterValue[0] === '@') {
                    $serviceName = substr($parameterValue, 1);
                    if ($parameterValue[1] === '@') {
                        $constructorParams[] = var_export($serviceName, true);
                    } else {
                        $constructorParams[] = "\$di->get(".var_export($serviceName, true).")";
                    }
                } else {
                    $constructorParams[] = var_export($parameterValue, true);
                }
            } elseif ($reflectionParameter->isDefaultValueAvailable()) {
                $parameterDefaultValue = $reflectionParameter->getDefaultValue();
                $constructorParams[] = var_export($parameterDefaultValue);
            } else {
                throw new Exception\ConfigException(sprintf(
                    System::_('Missing constructor parameter "%s" of class "%s"'),
                    $parameterName,
                    $className
                ));
            }
        }
        $body .= ");\r\n";
        
        
        
        $shared = 'false';
        if (isset($config['shared'])) {
            $shared = $config['shared'] ? 'true' : 'false';
        }
        return "[function (\$di) { $body }, $shared]";
    }
}
