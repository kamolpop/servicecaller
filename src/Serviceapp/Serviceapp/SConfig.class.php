<?php
namespace Serviceapp\Serviceapp;
class SConfig {

	public static $config;
	
/**
 * [Use for get value of config parameter]
 * @param  [String] $index [index of config array]
 * @return [String]        [description]
 */
	public static function get($index)
	{
		if(empty(self::$config)){
			self::$config = include("configs.php");
			self::$config['token_key'] = '_jobthai_token='.self::$config["client_id"];
		}
		return self::$config[$index];
	}
}
?>