<?php
/*
 |------------------------------------------------------------------
 | frame.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16-8-11 上午9:28
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Util;

class Log
{
	/**
	 * @param string $file
	 * @param string $message
	 * @param int    $level
	 *
	 * @return bool
	 */
	public static function writeLog($file, $message, $level)
	{
		$state = FALSE;
		$file = \app()->getConfig()->get('LOG_PATH') . '/' . date(\app()->getConfig()->get('LOG_ARCHIVE_TYPE')) . '/' . $file;
		if (\is_string($file) && \is_string($message) && \is_numeric($level)) {
			if (is_file($file)) {
				$path = realpath(dirname($file));
			} else {
				$path = dirname($file);
			}

			if (!is_dir($path)) {
				\mkdir($path, 0777, TRUE);
			}
			list($usec, $sec) = \explode(' ', microtime());
			$dateTime = date("Y-m-d H:i:s" . " {$usec}");
			$hostName = gethostname();
			$message = "[{$dateTime}]  {$hostName}  level:{$level}  " . $message . "\r\n";
			$state = \error_log($message, 3, $file);
		}
		return $state;
	}
}
