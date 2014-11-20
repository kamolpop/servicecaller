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
		
		if( strpos( $index , 'default')!==false ){
			$conf 	=	self::$config[preg_replace("/^(default_)/", "" , $index)];
			$conf 	=	$conf[self::$config[$index]];
		}else{
			$conf 	=	self::$config[$index];
		}

		return $conf;
	}

}
?>