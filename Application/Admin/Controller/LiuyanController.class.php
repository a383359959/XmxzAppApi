<?php
namespace Admin\Controller;
use Think\Controller;
class LiuyanController extends Controller{

 /*
	  *功能：留言功能管理
	  *作者：周珊珊
	  *时间：7-18
	  *修改时间
	  */
	 /*查看留言*/
	 public function message(){
	 	   	$count = M('liuyan')->count();
			$page = new \Think\Page($count,5);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$list = M('liuyan')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			
			$pageButton = $page->show();
			$this->assign('list',$list);
			
			$this->assign('pageButton',$pageButton);
			$this->display();
		}
	 /*删除留言*/
	 public function message_del(){
	 		M('liuyan')->where('id = '.$_REQUEST['id'])->delete();
			$this->success('删除成功');
	 }

}