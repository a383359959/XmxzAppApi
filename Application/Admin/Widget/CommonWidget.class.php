<?php

namespace Admin\Widget;

use Think\Controller;

class CommonWidget extends Controller{
	
	public function top(){
		$user = M('admin')->where('id = '.session('admin_user_id'))->find();
		$this->assign('user',$user);
		$this->display('Common:top');
	}
	
	public function left(){
		$this->display('Common:left');
	}
	
}
