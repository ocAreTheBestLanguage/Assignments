<?php
namespace Tamce\BBT\Core;

use ElfStack\Renderer;
use Exception;
use SplEnum;

class Helper
{
	const SALT_LENGTH = 15;
	static public function encryptPassword($password, $salt = null)
	{
		if (empty($salt)) {
			$salt = self::randomString(self::SALT_LENGTH);
		}
		return $salt . crypt($password, $salt);
	}

	static public function validatePassword($password, $hash)
	{
		return hash_equals($hash, self::encryptPassword($password, substr($hash, 0, self::SALT_LENGTH)));
	}

	static public function randomString($num, $dic = null)
	{
		if (empty($dic)) {
			$dic = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		}
		if ($num < 0) {
			$num *= -1;
		}
		$result = '';
		for ($i = 0; $i < $num; ++$i) {
			$result .= $dic[mt_rand(0, strlen($dic) - 1)];
		}
		return $result;
	}

	static public function abort($statusCode = null)
	{
		if (empty($statusCode)) {
			Renderer::render('errors/default', [], 500);
			die();
		}
		Renderer::render('errors/'.$statusCode, [], $statusCode);
		die();
	}

	static public function treatPdoError(array $errorInfo, $throw = true)
	{
		if ($errorInfo[0] === '00000') {
			return true;
		}
		if ($errorInfo[1] === NULL and $errorInfo[2] === NULL) {
			return true;
		}
		if ($throw) {
			throw new Exception($errorInfo[2]);
		}
		return $errorInfo;
	}

	static public function loadConstants()
	{
		require_once(__DIR__ . '/Constants.php');
	}
}
