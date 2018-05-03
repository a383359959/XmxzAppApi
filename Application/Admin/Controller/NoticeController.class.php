<?php

namespace Admin\Controller;

use Think\Controller;

class NoticeController extends Controller{
	/*
		*功能：平台公告管理
		*作者：周珊珊
		*时间：7-10
		*修改时间：
	*/
	public function platforms(){
		$count = M('notice')->where('status = 0')->count();
		$page = new \Think\Page($count,5);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$list = M('notice')->limit($page->firstRow.','.$page->listRows)->order('id desc')->where('status = 0')->select();
		$pageButton = $page->show();
		$this->assign('list',$list);
		$this->assign('pageButton',$pageButton);
		$this->display();
	}
	/*
		*功能：添加公告
		
	*/
	public function platforms_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['title']=$_REQUEST['title'];
			$data['content']=htmlspecialchars($_REQUEST['content']);
			$data['status']=0;
			$data['addtime']=time();
			$data['rtime']=time();
			M('notice')->add($data);
			$this->success('添加成功',U('platforms'));	
			exit;
		}
		$this->display();
	}
	/*
		*功能：修改公告
		
	*/
	public function platforms_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['title'] = $_REQUEST['title'];
			$data['content'] = htmlspecialchars($_REQUEST['content']);
			$data['rtime'] = time();
			$data['status'] = 0;
			M('notice')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('platforms'));
			exit;
		}
		$row = M('notice')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	/*
	* 删除公告
	*/
	public function platforms_del(){
		M('notice')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('Notice/platforms'));
	}
	/*
		*功能：商家公告管理
		*作者：周珊珊
		*时间：7-10
		*修改时间：
	*/
	public function business(){
		$count = M('notice')->where('status = 1')->count();
		$page = new \Think\Page($count,5);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$list = M('notice')->limit($page->firstRow.','.$page->listRows)->order('id desc')->where('status = 1')->select();
		
		$this->assign('list',$list);
		$this->assign('pageButton',$pageButton);
		$this->display();
	}
	/*
		*功能：添加公告
		
	*/
	public function business_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['title']=$_REQUEST['title'];
			$data['content']=htmlspecialchars($_REQUEST['content']);
			$data['status']=1;
			$data['addtime']=time();
			$data['rtime']=time();
			$data['s_id']=$_REQUEST['id'];
			M('notice')->add($data);
			$this->success('添加成功',U('business'));
		}
		$list=M('store')->select();
		$this->assign('list',$list);
		$this->display();
	}
	/*
		*功能：修改公告
		
	*/
	public function business_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['title']=$_REQUEST['title'];
			$data['content']=htmlspecialchars($_REQUEST['content']);
			$data['rtime']=time();
			$data['status']=$_REQUEST['status'];
			$data['recid']=$_REQUEST['id'];
			M('notice')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('business'));
			exit;
			
		}
		$row = M('notice')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	/*
	* 删除公告
	*/
	public function business_del(){
		M('notice')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('business'));
	}
	
}