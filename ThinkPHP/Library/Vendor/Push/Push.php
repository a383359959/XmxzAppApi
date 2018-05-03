<?php

header('Content-Type: text/html; charset=utf-8');
require_once(dirname(__FILE__).'/'.'IGt.Push.php');
require_once(dirname(__FILE__).'/'.'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__).'/'.'igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__).'/'.'igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__).'/'.'IGt.Batch.php');
require_once(dirname(__FILE__).'/'.'igetui/utils/AppConditions.php');

class Push{
	
	public $appid = 'PhY4xkAeta7vBesCTDZDH7';
	public $appkey = 'c8nPgNzFsk6DQ8btiU6lg7';
	public $mastersecret = 'tuJwakeq3GAy4fhUgIb4UA';
	public $peisong;
	
	
	public function __construct($_config){
		$this->peisong = M('peisong')->where('id = '.$_config['peisong_id'])->find();
	}
	
	public function pushMessageToSingle(){
		$igt = new IGeTui(NULL,$this->appkey,$this->mastersecret,false);
		$template = $this->IGtTransmissionTemplate();
		$message = new IGtSingleMessage();
		$message->set_isOffline(true);
		$message->set_offlineExpireTime(3600 * 12 * 1000);
		$message->set_data($template);
		$message->set_PushNetWorkType(0);
		$target = new IGtTarget();
		$target->set_appId($this->appid);
		$target->set_clientId($this->peisong['clientid']);
		try{
			$rep = $igt->pushMessageToSingle($message,$target);
			// print_r($rep);
		}catch(RequestException $e){
			$requstId = e.getRequestId();
			$rep = $igt->pushMessageToSingle($message,$target,$requstId);
			// var_dump($rep);
			// echo ("<br><br>");
		}
	}

	function IGtTransmissionTemplate(){
		$template = new IGtTransmissionTemplate();
		$template->set_appId($this->appid);
		$template->set_appkey($this->appkey);
		$template->set_transmissionType(2);
		$data = array(
			'title' => '爱超配送端',
			'content' => '您有新的订单',
			'payload' => array()
		);
		$template->set_transmissionContent(json_encode($data));
		$apn = new IGtAPNPayload();
		$alertmsg = new DictionaryAlertMsg();
		$alertmsg->body = 'body';
		$alertmsg->actionLocKey = 'ActionLockey';
		$alertmsg->locKey = 'LocKey';
		$alertmsg->locArgs = array('locargs');
		$alertmsg->launchImage = 'launchimage';
		$alertmsg->title = 'Title';
		$alertmsg->titleLocKey = 'TitleLocKey';
		$alertmsg->titleLocArgs = array('TitleLocArg');
		$apn->alertMsg = $alertmsg;
		$apn->badge = 7;
		$apn->sound = '';
		$apn->add_customMsg('payload','payload');
		$apn->contentAvailable = 1;
		$apn->category = 'ACTIONABLE';
		$template->set_apnInfo($apn);
		return $template;
	}
	
}