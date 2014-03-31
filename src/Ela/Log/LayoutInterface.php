<?php
namespace Ela\Log;

interface LayoutInterface
{
	public function output($logEvent);
}
