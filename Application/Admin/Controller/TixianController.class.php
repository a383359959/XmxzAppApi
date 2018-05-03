<?php

namespace Admin\Controller;

use Think\Controller;

class TixianController extends Controller{
	
	public function store_tixian(){
		if($_REQUEST['form_submit'] == 'ok'){
			$ids = implode(',',$_REQUEST['ids']);
			$_config = array(
				'detail_data' => $this->format_alipay($_REQUEST['ids']),
				'batch_num' => count($_REQUEST['ids']),
				'batch_fee' => M('store_cash')->where('id in ('.$ids.')')->getField('SUM(price)'),
				'notify_url' => 'http://api.smdouyou.com/store_notify_url.php'
			);
			$this->alipay($_config);
		}
		$list = M('store_cash')->order('add_time desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function peisong_tixian(){
		if($_REQUEST['form_submit'] == 'ok'){
			$ids = implode(',',$_REQUEST['ids']);
			$_config = array(
				'detail_data' => $this->format_alipay($_REQUEST['ids'],'peisong_cash'),
				'batch_num' => count($_REQUEST['ids']),
				'batch_fee' => M('peisong_cash')->where('id in ('.$ids.')')->getField('SUM(price)'),
				'notify_url' => 'http://api.smdouyou.com/peisong_notify_url.php'
			);
			$this->alipay($_config);
		}
		$list = M('peisong_cash')->order('add_time desc')->select();
		foreach($list as $key => $value){
			$peisong = M('peisong')->where('id = '.$value['peisong_id'])->find();
			$value['name'] = $peisong['name'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function user_tixian(){
		if($_REQUEST['form_submit'] == 'ok'){
			$ids = implode(',',$_REQUEST['ids']);
			$_config = array(
				'detail_data' => $this->format_alipay($_REQUEST['ids'],'users_cash'),
				'batch_num' => count($_REQUEST['ids']),
				'batch_fee' => M('users_cash')->where('id in ('.$ids.')')->getField('SUM(price)'),
				'notify_url' => 'http://api.smdouyou.com/user_notify_url.php'
			);
			$this->alipay($_config);
		}
		$list = M('users_cash')->order('add_time desc')->select();
		foreach($list as $key => $value){
			$users = M('users')->where('id = '.$value['user_id'])->find();
			$user_info = unserialize($users['user_info']);
			$value['nickname'] = base64_decode($user_info['nickname']);
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function user_tixian_old(){
		$list = M('users_tixian')->order('add_time desc')->select();
		foreach($list as $key => $value){
			$user = M('users')->where('id = '.$value['user_id'])->find();
			$user_info = unserialize($user['user_info']);
			$value['nickname'] = base64_decode($user_info['nickname']);
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function tixian_act(){
		$data['status'] = $_REQUEST['status'];
		M('users_tixian')->where('id = '.$_REQUEST['id'])->save($data);
		$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	public function format_alipay($ids,$table = 'store_cash'){
		$arr = array();
		foreach($ids as $key => $value){
			$cash = M($table)->where('id = '.$value)->find();
			$arr[] = $cash['id'].'^'.$cash['alipay_accounts'].'^'.$cash['alipay_name'].'^'.$cash['price'].'^提现';
		}
		return implode('|',$arr);
	}
	
	public function alipay($arr){
		$_config = array(
			'_input_charset' => 'UTF-8',
			'service' => 'batch_trans_notify',
			'partner' => '2088521653659439',
			'notify_url' => $arr['notify_url'],
			'account_name' => '沈阳鑫赢福商贸有限公司',
			'detail_data' => $arr['detail_data'],
			'batch_no' => date('YmdHis'),
			'batch_num' => $arr['batch_num'],
			'batch_fee' => $arr['batch_fee'],
			'email' => 'jiyang10301@126.com',
			'pay_date' => date('Ymd'),
		);		
		$_config['sign'] = $this->alipay_sign($_config);
		$_config['sign_type'] = 'MD5';
		$url = 'https://mapi.alipay.com/gateway.do?';
		$options = array();
		foreach($_config as $key => $value){
			$options[] = $key.'='.$value;
		}
		$url .= implode('&',$options);
		header('location:'.$url);
	}
	
	public function alipay_sign($arr){
		ksort($arr);
		reset($arr);
		$arg  = '';
		while(list($key,$value) = each($arr)){
			$arg .= $key.'='.$value.'&';
		}
		$arg = substr($arg,0,count($arg) - 2);
		if(get_magic_quotes_gpc()) $arg = stripslashes($arg);
		$arg .= 'psdnc99wuxvwsc025a5x5wbvq3ku7rjo';
		return md5($arg);
	}
	
}