<?php

namespace Api\Controller;

use Think\Controller;

class FeedbackController extends Controller{

	public function index(){
		$data['user_id'] = $_REQUEST['user_id'];
		$data['add_time'] = time();
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['content'] = $_REQUEST['content'];
		M('feedback')->add($data);
		die(json_encode(array('status' => 'success')));
	}
	
}