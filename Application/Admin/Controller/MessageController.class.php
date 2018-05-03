<?php

namespace Admin\Controller;

use Think\Controller;

class MessageController extends Controller{
	/*
		*功能：站内信管理
		*作者：周珊珊
		*时间：7-12
		*修改时间：
	*/
		public function index(){
			$count = M('message')->count();
			$page = new \Think\Page($count,5);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$list = M('message')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			$pageButton = $page->show();
			$this->assign('list',$list);
			$this->assign('pageButton',$pageButton);
			$this->display();
		}
		

		public function add(){

			if($_REQUEST['form_submit'] == 'ok'){
				$data['time']=time();
				$data['recid']=$_REQUEST['id'];
				$data['content']=htmlspecialchars($_REQUEST['content']);
				M('message')->add($data);
				$this->success('添加成功',U('index'));	
				exit;
			}else{
				$list=M('store')->select();
				$this->assign('list',$list);
				$this->display();
			}
		}

		public function detail(){
				$id=$_GET['id'];

				$list=M('message')->where("id=$id")->select();
				foreach ($list as $key => $value) {
					$a=$value['recid'];
				}
				$row=M('store')->where("id=$a")->select();
				$this->assign('row',$row);
				$this->assign('list',$list);
				$this->display();
			
		}

		public function del(){
			M('message')->where('id = '.$_REQUEST['id'])->delete();
			$this->success('删除成功',U('message/index'));
	}
		public function edit(){
			if($_REQUEST['form_submit'] == 'ok'){
				$data['time']=time();
				$data['recid']=$_REQUEST['id'];
				$data['content']=htmlspecialchars($_REQUEST['content']);
				M('message')->where('id = '.$_REQUEST['id'])->save($data);
				$this->success('修改成功',U('message/index'));
				exit;
			
		}
				$row = M('message')->where('id = '.$_REQUEST['id'])->find();
				$list=M('store')->select();
				$this->assign('list',$list);
				$this->assign('row',$row);
				$this->display();
		}
		
}