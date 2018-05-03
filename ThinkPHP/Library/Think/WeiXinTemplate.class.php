<?php

namespace Think;

class WeiXinTemplate{
	
	public $appid = 'wx45fae74da5a3cb9d';
	
	public $appscrect = 'a887c59a755cc58e78c85732ff7a54e7';
	
	public $access_token = '';
	
	public $status = '';
	
	public function __construct($status){
		$this->status = $status;
		$this->access_token = $this->access_token();
	}
	
	public function send($openid,$msg,$order_id){
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token;
		$_config = array(
			'touser' => $openid,
			'template_id' => 'OLWm8qYcD_3A8UcZEH3SiGpIXVgowfitewWytj6hQ3A',
			'url' => 'https://api.smdouyou.com/index.php/Home/Order/order_detail/order_id/'.$order_id.'.html',
			'data' => $msg
		);
		if($this->status == 1) $_config['url'] = 'https://api.smdouyou.com/index.php/Store/Order/orderCheck.html?id='.$order_id;
		$result = $this->postData($url,$_config);
		

	}
	
	public function access_token(){
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appscrect;
		$result = file_get_contents($url);
		$result = json_decode($result,true);
		return $result['access_token'];
	}
	
	function postData($url,$data){
		$data = json_encode($data);
		$data = str_replace('\\\n','\n',$data);
		$data = str_replace('\\\r','\r',$data);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'content' => $data,
				'timeout' => 15 * 60,
				'header' => 'Content-type:application/json;encoding=utf-8'
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url,false,$context);
		$result = json_decode(json_encode(simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA)),true);
		return $result;
	}
	
}