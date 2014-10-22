<?php
namespace Serviceapp\Serviceapp;
use Serviceapp\Serviceapp\CoreOperation;
class Serviceapp {

	private static $uri;
	private static $method;
	private static $ID;

	public static function call( $method = 'GET' , $uri , $id = '' )
	{
		$method = strtoupper($method);
		$method = ($method == 'UPDATE')?"PUT":$method;
		return new CoreOperation($uri,$method,$id);
	}
}

