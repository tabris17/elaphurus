<?php
namespace Ela\Log;

/**
 * 日志输出器接口
 */
interface AppenderInterface
{
	/**
	 * 输出日志
	 * 
	 * @param LogEvent $logEvent
	 * @return null
	 */
	public function append($logEvent);
	
	/**
	 * 获得日志输出器的格式
	 * 
	 * @return LayoutInterface
	 */
	public function getLayout();
	
	/**
	 * 设置日志输出器的格式
	 * 
	 * @param LayoutInterface $layout
	 * @return null
	 */
	public function setLayout($layout);

	/**
	 * 开启输出器
	 * 
	 * @return null
	 */
	public function start();
	
	/**
	 * 停止输出器
	 * 
	 * @return null
	 */
	public function stop();
	
	/**
	 * 返回输出器开启状态
	 * 
	 * @return boolean
	 */
	public function isStarted();
}
