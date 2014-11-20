<?php
namespace Serviceapp\Serviceapp;
use Serviceapp\Serviceapp\SConfig;
use Serviceapp\Serviceapp\Authen;
use Serviceapp\Serviceapp\SRequest;
use Serviceapp\Serviceapp\CURL;
use \Session;

class CoreOperation {
       
    private $_param         = array();
    private $_data          = array();
    private $input          = array();
    private $file_data      = array();
    private $request_time;
    private $err;
    private $error; 
    private $rawResponse;
    private $service;
    private $_ID;
    private $method;
    private $result;
    private $protocal; 
    private $totalTime;
    private $atr = false;


    /**
     * [__construct Check token before sending request]
     * @param [type] $service [route & prefix]
     * @param [type] $method  [method]
     * @param string $ID      []
     */
    public function __construct( $service , $method , $ID = '' )
    {  
        $this->_ID      = $ID;
        $this->service  = $service;
        $this->method   = $method;
    }

    public function setParams($param , $value = '')
    {
        $this->makeArray('param', $param , $value);
        return $this;
    }

    public function setInput($data , $value = ''){

        if($this->method == 'PUT' && empty($this->input['_method'])){
            $this->method            = 'POST';
            $this->_data['_method']  = 'PUT';
        }

        $this->makeArray('data' , $data , $value);
        $this->input = $this->_data;

        return $this;
    }

    private function makeArray( $type , $data , $value ){
        if(is_array($data)){
            foreach ($data as $key => $val) {

                if($type=='data' ){ $this->_data[$key] = $val; }
                if($type=='param'){ $this->_param[$key] = $val; }
            }
        }else{

            if($type=='data' ){ $this->_data[$data] = $value;}
            if($type=='param'){ $this->_param[$data] = $value;} 
        }
    } 


    public function IsFiles($f){
        
        foreach ($f as $key => $value) {
            if(isset($f[$key]['tmp_name'])&&isset($f[$key]['type'])&&isset($f[$key]['size']))
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * [upload any files by get_content]
     * @param  array  $files 
     * @return this object        
     */
    public function upload($files=array())
    {
        
        $files=(empty($files))?$_FILES:$files;
        if(!$this->IsFiles($files))return $this;

        foreach ($files as $key => $file) {
            $fileKey   =   $key;
            if($file['size'] > 0 && $file['error']==0){
                
                $fc = file_get_contents($file['tmp_name']);
                $this->file_data[$fileKey]              = $file;
                $this->file_data[$fileKey]['content']   = base64_encode($fc);
                $this->file_data[$fileKey]['MD5']       = md5($this->file_data[$fileKey]['content']);
                unset($this->file_data[$fileKey]['tmp_name']);
                unset($this->file_data[$fileKey]['error']);
                $this->input[$fileKey] = $this->file_data[$fileKey];
            }
        }
        // $this->input['files'] = serialize($this->file_data);
        return $this; 
    }

    private function setProtocal(){
        $protocal   = (empty($this->protocal)?SConfig::get("protocal"):$this->protocal);
        $protocal   = (empty($protocal)?'http':$protocal)."://";
        return $protocal;
    }


    private function genUrl()
    {
        $protocal   =  $this->setProtocal();
        $host       =  SConfig::get("host");
        $host       =  (strpos($host, "http://")||strpos($host, "https://"))?$host:$protocal.$host;
        $prefix     =  $this->service;
        $real_url   =  $host."/".$prefix;

        if(!empty($this->_ID)){
            $real_url .= "/".$this->_ID;
        }
        if(!empty($this->_param))
        {
            $params       =  http_build_query($this->_param); 
            $real_url    .=  "?".$params;
        }
        return $real_url;
    } 
    
    public function useHttps(){
        $this->protocal = "https";
        return $this;
    }

    public function execute()
    {
        $request            =   new SRequest;
        $url                =   $this->genUrl();
        $method             =   $this->method;
        $input              =   $this->input;
        $isHttps            =   ($this->protocal=='https')?true:false;
        $req                =   $request->send( $url , $method , $input , $isHttps);
        $this->request_time =   $request->getTime();
        $this->AuthenTime   =   $request->getAuthenTime();
        $this->error        =   $request->getError();
        $this->rawResponse  =   $request->getRaw();
        $res_decode         =   json_decode($req);

        if(is_object($res_decode)){

            $callResult     =   $res_decode;
            $this->err      =   $callResult->header->errno;

            if($this->err == 1 || $this->err == 2){

                $this->result   = $callResult;
            }else{  
                $this->result   =   $req;
            }

        }
        return $this;
        
    }

    public function getResult($assoc = false){

        $result     =   json_decode($this->result,$assoc);
        $result     =   $this->getBody($result,$assoc);
        return $result;
    }

    public function getError(){
        return $this->error;
    }  

    public function Raw(){
        return $this->rawResponse;
    }

    private function getBody($obj , $assoc){
        if (empty($obj)) {
            return null;
        }
        if($assoc){
            if(!isset($obj['body']['type'])){
                $body = $obj['body'];
                return $body;
            }elseif($obj['body']['type'] == 'pdf'){
                header("Content-type: application/pdf");
                header("content-disposition:attachment;filename='pdf".date("YmdHis").".".$obj['body']['type']);
                echo $body = base64_decode($obj['body']['content']);exit;
            } 
        }else{
            if(!isset($obj->body->type)){
                $body = $obj->body;
                return $body;
            }elseif($obj->body->type == 'pdf'){
                header("Content-type: application/pdf");
                header("content-disposition:attachment;filename='pdf".date("YmdHis").".".$obj->body->type);
                echo $body = base64_decode($obj->body->content);exit;
            } 
        }
    }
 
    public function info($req=''){
        
        $info['REQUEST-STATUS']     =   ($this->err!=0)?"Fail":"Success";
        $info['ATR']                =   ($this->atr)?"TRUE":'FALSE';
        $info['REQUEST-TIME']       =   $this->request_time."s.";
        if(!empty(Session::get('auth_time'))){
            $info['AUTHEN-TIME']    =   Session::get('auth_time')."s.";
        }
        $info['TOTAL-TIME']         =   $this->request_time+@$info['AUTHEN-TIME']."s.";
        $info['DOMAIN']             =   SConfig::get('host');
        $info['URL']                =   $this->genUrl();
        $info['CLIENT-ID']          =   SConfig::get('client_id');
        $info['TOKEN-ID']           =   Session::get(SConfig::get('token_key'));
        $info['SERVICE-NAME']       =   $this->service;
        $info['METHOD']             =   $this->method;
        $info['URL-PARAMETERS']     =   $this->_param;
        $info['INPUT']              =   $this->_data;
        Session::forget('auth_time');

        $info   =   $this->cleanArray($info);
        return $info;
    }

    public function cleanArray($arr){
        foreach ($arr as $key => $value) {
            if(empty($value)){unset($arr[$key]);}
        }
        return $arr;
    }
    
    
}

?>