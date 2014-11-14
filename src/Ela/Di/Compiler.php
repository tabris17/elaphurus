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
    private $dependences = [
        '\Ela\Log\LoggerAwareInterface' => 'logger',
        '\Ela\Log\DiAwareInterface' => 'di',
    ];
    
    /**
     * 
     * @param string $interface 接口名。
     * @param string $service 服务名。
     * @return void
     */
    public function registerDependence($interface, $service)
    {
        $this->dependences[$interface] = $service;
    }
    
    /**
     * 
     * @param string $interface
     * @return void
     */
    public function unregisterDependence($interface)
    {
        unset($this->dependences[$interface]);
    }
    
    private function getDependenceSettingCode($className)
    {
        $code = '';
        foreach ($this->dependences as $interface => $service) {
            if (is_subclass_of($className, $interface)) {
                $setter = 'set'.ucfirst($service);
                $code .= "\$o->{$setter}(";
                $code .= "\$di->get('{$service}'));";
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
     *      'default' => true, // 注册为默认接口
     *      'params' => [
     *          'str_param1' => 'value1',
     *          '*service1' => 'logger',
     *      ],
     *      'properties' => [],
     *      'setters' => [
     *          '*service' => 'serviceName',
     *          'scalar' => '123',
     *      ],
     *      'methods' => [
     *          'method1' => ['p1' => 1, 'p2' => 2],
     *          'method2' => ['p1' => 1, 'p2' => 2],
     *      ],
     * ];
     * </code>
     * @param array $config 配置信息。
     * @param array $interfaces 输出类实现的接口。
     * @return string 返回 PHP 代码。
     */
    public function compile(array $config, &$interfaces = null)
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
        
        $getConfig = function ($name, $default) use ($config) {
            if (isset($config[$name])) {
                return $config[$name];
            }
            return $default;
        };
        
        $refClass = new \ReflectionClass($className);

        $body = $this->getConstructCode($refClass, $getConfig('params', []));
        $body .= $this->getPropertiesCode($getConfig('properties', []));
        $body .= $this->getSettersCode($refClass, $getConfig('setters', []));
        $body .= $this->getDependenceSettingCode($className);
        $body .= $this->getMethodsCode($refClass, $getConfig('methods', []));
        
        $shared = 'false';
        if (isset($config['shared'])) {
            $shared = $config['shared'] ? 'true' : 'false';
        }
        return "[function(\$di){{$body}},$shared]";
    }
    
    /**
     *
     * @param \ReflectionClass $refClass
     * @param array $config
     * @return string
     * @throws Exception\ConfigException
     */
    private function getSettersCode($refClass, $config)
    {
        $code = '';
        $className = $refClass->getName();
        
        foreach ($config as $setter => $value) {
            if ($setter[0] === '*') {
                $serviceName = $value;
                $methodName = 'set' . substr($setter, 1);
                $value = "\$di->get(".var_export($serviceName, true).")";
            } else {
                $methodName = "set$methodName";
                $value = var_export($value, true);
            }
            if (!method_exists($className, $methodName)) {
                throw new ConfigException(sprintf(
                    System::_('Unknown method "%s" of class "%s"'),
                    $methodName,
                    $className
                ));
            }
            
            $refMethod = $refClass->getMethod($methodName);
            if ($refMethod->getNumberOfRequiredParameters() > 1) {
                throw new ConfigException(sprintf(
                    System::_('Method "%s::%s" is not a setter'),
                    $className,
                    $methodName
                ));
            }
            
            $code .= "\$o->$methodName($value);";
        }
        
        return $code;
    }
    
    /**
     *
     * @param \ReflectionClass $refClass
     * @param array $config
     * @return string
     * @throws Exception\ConfigException
     */
    private function getMethodsCode($refClass, $config)
    {
        $code = '';
        $className = $refClass->getName();
        
        foreach ($config as $methodName => $params) {
            
            if (!method_exists($className, $methodName)) {
                throw new ConfigException(sprintf(
                    System::_('Unknown method "%s" of class "%s"'),
                    $methodName,
                    $className
                ));
            }
            
            $args = $this->getMethodArgs($refClass->getMethod($methodName), $params);
            $code .= "\$o->$methodName(" . implode(',', $args) . ');';
        }
        
        return $code;
    }
    
    /**
     *
     * @param array $config
     * @return string
     */
    private function getPropertiesCode($config)
    {
        $code = '';
        foreach ($config as $propertyName => $value) {
            if ($propertyName[0] === '*') {
                $serviceName = substr($propertyName, 1);
                $code .= "\$o->{$serviceName}=";
                $code .= "\$di->get(".var_export($serviceName, true).")";
            } else {
                $code .= "\$o->{$propertyName}=";
                $code .= var_export($value, true);
            }
            $code .= ';';
        }
        return $code;
    }
    
    /**
     * 
     * @param \ReflectionClass $refClass
     * @param array $config
     * @return string
     */
    private function getConstructCode($refClass, $config)
    {
        $className = $refClass->getName();
        $refConstructor = $refClass->getConstructor();
        if ($refConstructor === null) {
            return "\$o=new $className();";
        }
        $params = $this->getMethodArgs($refConstructor, $config);
        return "\$o=new $className(" . implode(',', $params) . ');';
    }
    
    /**
     * 
     * @param \ReflectionMethod $refMethod
     * @return array
     * @throws Exception\ConfigException
     */
    private function getMethodArgs($refMethod, $args)
    {
        $params = array();
        
        foreach ($refMethod->getParameters() as $refParam) {
            $paramName = $refParam->getName();

            if (isset($args[$paramName])) {
                $paramValue = $args[$paramName];
                $params[] = var_export($paramValue, true);
            } elseif (isset($args["*$paramName"])) {
                $serviceName = $args["*$paramName"];
                $params[] = "\$di->get(".var_export($serviceName, true).")";
            } elseif ($refParam->isDefaultValueAvailable()) {
                $paramDefaultValue = $refParam->getDefaultValue();
                $params[] = var_export($paramDefaultValue);
            } else {
                throw new Exception\ConfigException(sprintf(
                    System::_('Missing parameter "%s" of "%s::%s"'),
                    $paramName,
                    $refParam->getDeclaringClass()->getName(),
                    $refMethod->getName()
                ));
            }
        }
        return $params;
    }
}
