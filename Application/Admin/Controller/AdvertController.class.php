<?php

namespace Admin\Controller;

use Think\Controller;

class AdvertController extends Controller{
	/*
		*功能：广告管理
		*作者：周珊珊
		*时间：7-12
		*修改时间：
	*/


		/*
		 *广告显示
		 */
		public function index(){
			$count = M('advert')->count();
			$page = new \Think\Page($count,5);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$list = M('advert')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			
			$pageButton = $page->show();
			$this->assign('list',$list);
			
			$this->assign('pageButton',$pageButton);
			$this->display();
			
		}
		public function add(){
			if($_REQUEST['form_submit'] == 'ok'){
				$data['pic']=$_REQUEST['pic'];
				$data['addtime']=time();
				$data['ctime']=$_REQUEST['ctime'];
				$data['status']=0;
				M('advert')->add($data);
				$this->success('添加成功',U('index'));
			}
			$this->display();
		}
		public function del(){
			M('advert')->where('id = '.$_REQUEST['id'])->delete();
			$this->success('删除成功',U('index'));
		}
		public function edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['pic']=$_REQUEST['pic'];
			$data['ctime']=$_REQUEST['ctime'];
			$data['addtime']=time();
			$data['status']=$_REQUEST['status'];
			M('advert')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('index'));
			exit;
			
		}
		$row = M('advert')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
}