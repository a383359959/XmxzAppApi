<?php

namespace Think;

class WeiXin{
	
	public $appid = 'wx45fae74da5a3cb9d';
	
	public $appsecret = 'a887c59a755cc58e78c85732ff7a54e7';
	
	public $url = '';
	
	public $name = '';
	
	public function __construct($url = '',$name = ''){
		$this->url = empty($url) ? 'https://api.smdouyou.com/' : $url;
		$this->name = $name;
		if(isset($_REQUEST['code'])){
			$access_token = $this->access_token();
			if($access_token){
				$info = $this->get_user_info($access_token);
				if($info) header('location:'.$this->url);
			}else{
				die('token换取失败！');
			} 
		}else{
			$session_name = empty($this->name) ? session('openid') : session($this->name);
			if(!empty($session_name)){
				
			}else{
				$this->auth();
			}
		}
	}
	
	/*
	*	授权
	*/
	public function auth(){
		$url = !empty($this->url) && $this->url != 'https://api.smdouyou.com/' ? $this->url : 'https://api.smdouyou.com/index.php/Home/Index/school.html';
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($url).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		header('location:'.$url);
	}
	
	/*
	*	access_token
	*/
	public function access_token(){
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$_REQUEST['code'].'&grant_type=authorization_code';
		$result = file_get_contents($url);
		$result = json_decode($result,true);
		if($result['access_token']){
			if(empty($this->name)){
				session('openid',$result['openid']);
			}else{
				session($this->name,$result['openid']);
			}
			$_SESSION['access_token'] = $result['access_token'];
			$_SESSION['expires_in'] = $result['expires_in'];
			$_SESSION['refresh_token'] = $result['refresh_token'];
			return $result['access_token'];
		}else{
			return false;
		}
	}
	
	/*
	*	获取用户信息
	*/
	public function get_user_info($access_token){
		$session_name = empty($this->name) ? session('openid') : session($this->name);
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$session_name.'&lang=zh_CN';
		$result = file_get_contents($url);
		$result = json_decode($result,true);
		session('user_info',$result);
		return $result;
	}
	
}