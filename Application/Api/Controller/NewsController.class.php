<?php

namespace Api\Controller;

use Think\Controller;

class NewsController extends Controller{

	public function index(){
		$limit = (($_REQUEST['page'] - 1) * 10).',10';
		$list = M('news')->field('id,title,view,from_unixtime(add_time,"%Y-%m-%d %H:%i:%s") as add_time')->order('add_time desc')->limit($limit)->select();
		$result['list'] = $list;
		die(json_encode($result));
	}
	
	public function news_detail(){
		M('news')->where('id = '.$_REQUEST['news_id'])->setInc('view',1);
		$row = M('news')->field('title,view,content,from_unixtime(add_time,"%Y-%m-%d %H:%i:%s") as add_time')->where('id = '.$_REQUEST['news_id'])->find();
		die(json_encode($row));
	}
	
}