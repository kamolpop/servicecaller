<?php
require("CoreOperation.class.php");

class Jobthai {
	
	private static $uri;
	private static $method;
	private static $ID;

	public static function call( $method = 'GET' , $uri , $id = '')
	{
		$method = strtoupper($method);
		$method = ($method == 'UPDATE')?"PUT":$method;
		return new CoreOperation($uri,$method,$id);
	}
 
}
 
?>