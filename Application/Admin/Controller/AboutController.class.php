<?php

namespace Admin\Controller;

use Think\Controller;

class AboutController extends Controller{

	/*
		*功能：关于我们
		*时间：7-17
		*作者：周珊珊

	 */
	public function about_index(){
		    $count = M('about')->count();
			$page = new \Think\Page($count,5);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$list = M('about')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			
			$pageButton = $page->show();
			$this->assign('list',$list);
			
			$this->assign('pageButton',$pageButton);
			$this->display();
		
	}
	public function about_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['content']=htmlspecialchars($_REQUEST['content']);
			M('about')->add($data);
			$this->success('添加成功',U('about/about_index'));	
			exit;
		}
			$this->display();
	}
	public function about_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['content']=htmlspecialchars($_REQUEST['content']);
			M('about')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('About/about_index'));
			exit;
			
		}
		$row = M('about')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
		}
	
	 public function about_del(){
	 	M('about')->where('id = '.$_REQUEST['id'])->delete();
			$this->success('删除成功',U('about_index'));
	 }

	}