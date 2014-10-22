<?php
namespace Serviceapp\Serviceapp;
use Serviceapp\Serviceapp\CURL;
use Serviceapp\Serviceapp\ErrorResponse;
class SRequest {

	private $err_code ;
	private $err_no;
	private $reqTime;
	private $authTime;
	private $raw;

	public function __construct() {
	}

	public function send( $url , $method , $input = array() , $protocal){
			
			$err_number 	= 0;
			$ReqSW			= new CURL($url , $method , $input , $protocal);// url , method , input , protocal
		    $result 		= $ReqSW->getResult();
		    $this->raw 		= $result;
		    $httpCode 		= $ReqSW->getHttpCode();
			$header 		= $ReqSW->getHeader();
			$this->reqTime	= $ReqSW->getTime();
		    if($ReqSW->IsSuccess()){

		    	ini_set("memory_limit","256M");
		    	$resdec			= json_decode($result);
		    	@$err_number	= $resdec->header->errno;

		    	if(!is_object($resdec)){
		    		$err_number = 14;
					$this->err_code = "SERVICE WARNING : ".ErrorResponse::error_message($err_number)." -> ".$url."\n";
		    		$this->err_no 	= $err_number;
		    		return null;
		    	}
		    	if($err_number != 0){
		    		$this->err_code = "SERVICE ERROR : ".ErrorResponse::error_message($err_number)." -> ".$url."\n";
		    		$this->err_no 	= $err_number;
		    	}else{
		    		$this->err_code = "";
		    	}

		    }else{
		    	$this->err_code 	= "HTTP ERROR : ".ErrorResponse::error_message($httpCode)." -> ".$url."\n";
		    	$this->err_no 		= $httpCode;
		    	$result 			= null;
		    }

		    return $result;
	}

	public function getError(){
			return "\n[".$this->err_no.'] '.$this->err_code;
	}

	public function getTime(){
			return $this->reqTime;
	}

	public function getAuthenTime(){
			return $this->authTime;
	}

	public function getRaw(){
			return $this->raw;
	}

	

	

}


?>