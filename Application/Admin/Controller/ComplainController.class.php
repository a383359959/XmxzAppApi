<?php

namespace Admin\Controller;

use Think\Controller;

class ComplainController extends Controller{
/*
		*功能：投诉管理
		*作者：周珊珊
		*时间：7-13
		*修改时间：
	*/
	public function index(){
		$complain=M('complain');
		$list=$complain->select();
		$this->assign('list',$list);
		$this->display();
	}
	/*
		*功能：协议管理
		*作者：周珊珊
		*时间：7-14
		*修改时间：
	*/
	public function ocprotol(){
		$ocprotol = M('ocprotol')->select();
		$this->assign('ocprotol',$ocprotol);
		$this->display();
	}
	/*添加协议*/
	public function ocprotol_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['content']=htmlspecialchars($_REQUEST['content']);
			$data['time']=time();
			M('ocprotol')->add($data);
			
			$this->success('添加成功',U('Complain/ocprotol'));	
			exit;
			
		}
		$this->display();
	}
	/*修改协议*/
	public function ocprotol_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['content']=htmlspecialchars($_REQUEST['content']);
			$data['time']=time();
			M('ocprotol')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('Complain/ocprotol'));
			exit;
			
		}
		$row = M('ocprotol')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	/*删除协议*/
	public function ocprotol_del(){
		M('ocprotol')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('Complain/ocprotol'));
	}

}