<?php
namespace Admin\Controller;
use Think\Controller;
class KeywordController extends Controller{
	/*
		*功能：关键字管理
		*作者：周珊珊
		*时间:7-18
		*修改时间：7-19
	 */
	public function index(){
			$count = M('keyword')->count();
			$page = new \Think\Page($count,15);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$list = M('keyword')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			
			$pageButton = $page->show();
			$this->assign('list',$list);
			
			$this->assign('pageButton',$pageButton);
			$this->display();
	}
	/*
	添加关键词
	 */
	public function add(){
		header("Content-type: text/html; charset=utf-8");
		if($_REQUEST['form_submit'] == 'ok'){
			$a=$_REQUEST['aa'];
			$b=split(",",$a);
			$cou=count($b);
			for($i=0;$i<$cou;$i++){
				$s=$b[$i];
				$data['word']=$b[$i];
				M('keyword')->add($data);
				}
			$this->success('添加成功',U('index'));
			exit;
		}
		$this->display();	
	}
	/*删除关键字*/
	public function del(){
		M('keyword')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('index'));
	}
}