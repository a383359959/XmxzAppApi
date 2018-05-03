<?php

namespace Api\Controller;

use Think\Controller;

class ShowController extends Controller{

	public function index(){
		$limit = (($_REQUEST['page'] - 1) * 10).',10';
		$list = M('show')->field('id,title,url,view,from_unixtime(add_time,"%Y-%m-%d %H:%i:%s") as add_time')->where('pid = 0')->order('add_time desc')->limit($limit)->select();
		$result['list'] = $list;
		die(json_encode($result));
	}

	public function lists(){
		$limit = (($_REQUEST['page'] - 1) * 10).',10';
		$list = M('show')->field('id,title,url,view,from_unixtime(add_time,"%Y-%m-%d %H:%i:%s") as add_time')->where('pid = '.$_REQUEST['pid'])->order('add_time desc')->limit($limit)->select();
		$result['list'] = $list;
		$result['title'] = M('show')->where('id = '.$_REQUEST['pid'])->getField('title');
		die(json_encode($result));
	}
		
}