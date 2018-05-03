<?php

namespace Admin\Controller;

use Think\Controller;

class StoreController extends Controller{
	
	public function index(){
		$this->display();
	}
	
	public function index_add(){
		$this->display();
	}
	
	public function category(){
		$category = M('category')->where('parent_id = 0')->order('sort asc')->select();
		foreach($category as $key => $value){
			$value['child'] = M('category')->where('parent_id = '.$value['id'])->order('sort asc')->select();
			$category[$key] = $value;
		}
		$this->assign('category',$category);
		$this->display();
	}
	
	public function category_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['parent_id'] = $_REQUEST['parent_id'];
			$data['sort'] = $_REQUEST['sort'];
			M('category')->add($data);
			$this->success('添加成功',U('category'));
			exit;
		}
		$category = M('category')->where('parent_id = 0')->select();
		$this->assign('category',$category);
		$this->display();
	}
	
	public function category_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['parent_id'] = $_REQUEST['parent_id'];
			$data['sort'] = $_REQUEST['sort'];
			M('category')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('category'));
			exit;
		}
		$row = M('category')->where('id = '.$_REQUEST['id'])->find();
		$category = M('category')->where('parent_id = 0')->select();
		$this->assign('category',$category);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function category_delete(){
		M('category')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('category'));
	}
	
	public function user(){
		$list = M('store_user')->order('id desc')->select();
		foreach($list as $key => $value){
			$row = M('category')->where('id = '.$value['category_id'])->find();
			$value['category'] = $row['name'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function user_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$row = M('store_user')->where('username = "'.$_REQUEST['username'].'" and category_id = '.$_REQUEST['category_id'])->find();
			if($row) $this->error('用户名已经存在');
			$data['username'] = $_REQUEST['username'];
			$data['password'] = md5($_REQUEST['password']);
			$data['category_id'] = $_REQUEST['category_id'];
			$data['add_time'] = time();
			M('store_user')->add($data);
			$this->success('添加成功',U('user'));
			exit;
		}
		$category = M('category')->where('parent_id = 0')->select();
		$this->assign('category',$category);
		$this->display();
	}
	
	public function user_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$row = M('store_user')->where('id <> '.$_REQUEST['id'].' and username = "'.$_REQUEST['username'].'" and category_id = '.$_REQUEST['category_id'])->find();
			if($row) $this->error('用户名已经存在');
			$data['username'] = $_REQUEST['username'];
			if(!empty($_REQUEST['password'])) $data['password'] = md5($_REQUEST['password']);
			$data['category_id'] = $_REQUEST['category_id'];
			M('store_user')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('user'));
			exit;
		}
		$category = M('category')->where('parent_id = 0')->select();
		$row = M('store_user')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->assign('category',$category);
		$this->display();
	}
	
	public function supermarket(){
		$list = M('store')->order('sort asc')->where('type = 1')->select();
		foreach($list as $key => $value){
			$school = M('school')->where('id = '.$value['school_id'])->find();
			$value['school'] = $school['name'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function supermarket_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$find = M('store_user')->where('username = "'.$_REQUEST['username'].'"')->find();
			if($find){
				$this->error('用户名已经存在');
			}else{
				$user['username'] = $_REQUEST['username'];
				$user['password'] = md5($_REQUEST['password']);
				$user['add_time'] = time();
				$user['type'] = 1;
				$user_id = M('store_user')->add($user);
				$data['user_id'] = $user_id;
				$data['school_id'] = $_REQUEST['school_id'];
				$data['type_id'] = $_REQUEST['type_id'];
				$data['category_id'] = $_REQUEST['category_id'];
				$data['sort'] = $_REQUEST['sort'];
				$data['logo'] = $_REQUEST['logo'];
				$data['pei_price'] = empty($_REQUEST['pei_price']) ? '0.00' : $_REQUEST['pei_price'];
				$data['address'] = $_REQUEST['address'];
				$data['store_name'] = $_REQUEST['store_name'];
				$data['desc'] = $_REQUEST['desc'];
				$data['dabao_price'] = empty($_REQUEST['dabao_price']) ? '0.00' : $_REQUEST['dabao_price'];
				$data['price_qisong'] = empty($_REQUEST['price_qisong']) ? '0.00' : $_REQUEST['price_qisong'];
				$data['is_chongzhi'] = $_REQUEST['is_chongzhi'];
				$data['time_period'] = $_REQUEST['time_period'];
				$data['dashang_price'] = empty($_REQUEST['dashang_price']) ? '0.00' : $_REQUEST['dashang_price'];
				$data['type'] = 1;
				M('store')->add($data);
				$this->success('添加成功',U('supermarket'));
				exit;
			}
		}
		$school = M('school')->order('sort asc')->select();
		$category = M('category')->where('parent_id = 1')->order('sort asc')->select();
		$this->assign('category',$category);
		$this->assign('school',$school);
		$this->display();
	}
	
	public function supermarket_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			if($_REQUEST['username'] == $_REQUEST['old_username']){
				if(!empty($_REQUEST['password'])){
					$user['password'] = md5($_REQUEST['password']);
					M('store_user')->where('id = '.$_REQUEST['user_id'])->save($user);
				}
				$user_id = $_REQUEST['user_id'];
			}else{
				$find = M('store_user')->where('id <> '.$_REQUEST['user_id'].' and username = "'.$_REQUEST['username'].'"')->find();
				if($find){
					$this->error('用户名已经存在');
				}else{
					$user['username'] = $_REQUEST['username'];
					$user['password'] = md5($_REQUEST['password']);
					$user['type'] = 1;
					$user_id = M('store_user')->add($user);
				}
			}
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			$data['type_id'] = $_REQUEST['type_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			$data['sort'] = $_REQUEST['sort'];
			$data['logo'] = $_REQUEST['logo'];
			$data['pei_price'] = empty($_REQUEST['pei_price']) ? '0.00' : $_REQUEST['pei_price'];
			$data['address'] = $_REQUEST['address'];
			$data['store_name'] = $_REQUEST['store_name'];
			$data['desc'] = $_REQUEST['desc'];
			$data['dabao_price'] = empty($_REQUEST['dabao_price']) ? '0.00' : $_REQUEST['dabao_price'];
			$data['price_qisong'] = empty($_REQUEST['price_qisong']) ? '0.00' : $_REQUEST['price_qisong'];
			$data['is_chongzhi'] = $_REQUEST['is_chongzhi'];
			$data['time_period'] = $_REQUEST['time_period'];
			$data['pei_time'] = $_REQUEST['pei_time'];
			$data['dashang_shouxu_price'] = $_REQUEST['dashang_shouxu_price'];
			$data['dashang_price'] = empty($_REQUEST['dashang_price']) ? '0.00' : $_REQUEST['dashang_price'];
			$data['type'] = 1;
			M('store')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('supermarket'));
			exit;
		}
		$row = M('store')->where('id = '.$_REQUEST['id'])->find();
		$store_user = M('store_user')->where('id = '.$row['user_id'])->find();
		$row['username'] = $store_user['username'];
		$school = M('school')->order('sort asc')->select();
		$category = M('category')->where('parent_id = 1')->order('sort asc')->select();
		$this->assign('category',$category);
		$this->assign('school',$school);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function supermarket_commodity(){
		$list = M('store_category')->where('store_id = '.$_REQUEST['store_id'])->order('sort asc')->select();
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->assign('list',$list);
		$this->display();
	}
	
	public function supermarket_commodity_category(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			$data['store_id'] = $_REQUEST['store_id'];
			M('store_category')->add($data);
			$this->success('添加成功',U('supermarket_commodity',array('store_id' => $_REQUEST['store_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->display();
	}
	
	public function supermarket_commodity_category_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			$data['store_id'] = $_REQUEST['store_id'];
			M('store_category')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('supermarket_commodity',array('store_id' => $_REQUEST['store_id'])));
			exit;
		}
		$row = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function supermarket_commodity_category_delete(){
		M('store_category')->where('id = '.$_REQUEST['category_id'])->delete();
		$this->success('删除成功',U('supermarket_commodity',array('store_id' => $_REQUEST['store_id'])));
	}
	
	public function supermarket_commodity_goods(){
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$list = M('store_category_goods')->where('store_id = '.$_REQUEST['store_id'].' and category_id = '.$_REQUEST['category_id'])->order('id desc')->select();
		$this->assign('store',$store);
		$this->assign('list',$list);
		$this->assign('category',$category);
		$this->display();
	}
	
	public function supermarket_commodity_goods_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['goods_name'] = $_REQUEST['goods_name'];
			$data['litpic'] = $_REQUEST['litpic'];
			$data['images'] = $_REQUEST['images'];
			$data['price'] = $_REQUEST['price'];
			$data['attr'] = $_REQUEST['attr'];
			$data['youhui'] = $_REQUEST['youhui'];
			$data['zhekou'] = $_REQUEST['zhekou'];
			$data['qianggou_time'] = $_REQUEST['qianggou_time'];
			$data['store_id'] = $_REQUEST['store_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			M('store_category_goods')->add($data);
			$this->success('添加成功',U('supermarket_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$this->assign('store',$store);
		$this->assign('category',$category);
		$this->display();
	}
	
	public function supermarket_commodity_goods_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['goods_name'] = $_REQUEST['goods_name'];
			$data['litpic'] = $_REQUEST['litpic'];
			$data['images'] = $_REQUEST['images'];
			$data['price'] = $_REQUEST['price'];
			$data['attr'] = $_REQUEST['attr'];
			$data['store_id'] = $_REQUEST['store_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			$data['youhui'] = $_REQUEST['youhui'];
			$data['zhekou'] = $_REQUEST['zhekou'];
			$data['qianggou_time'] = $_REQUEST['qianggou_time'];
			M('store_category_goods')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('supermarket_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$row = M('store_category_goods')->where('id = '.$_REQUEST['id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$this->assign('store',$store);
		$this->assign('category',$category);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function supermarket_commodity_goods_delete(){
		M('store_category_goods')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('supermarket_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
	}
	
	public function food(){
		$where = 'type = 2';
		if($_REQUEST['school_id'] != '') $where .= ' and school_id = '.$_REQUEST['school_id'];
		
		$count = M('store')->where($where)->count();
		$page = new \Think\AdminPage($count,15);
		
		$list = M('store')->order('sort asc')->limit($page->firstRow.','.$page->listRows)->where($where)->select();
		
		foreach($list as $key => $value){
			$school = M('school')->where('id = '.$value['school_id'])->find();
			$value['school'] = $school['name'];
			// $value['today_price'] = getPrice($value['id'],0);
			// $value['today_price_ziying'] = getPrice($value['id'],1);
			$list[$key] = $value;
		}
		
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		
		$this->display();
	}
	
	public function food_xiaoliang(){
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		if($_REQUEST['type'] == 'day'){
			$list = M('store_sale')->field('*,sum(ziying) as ziying,sum(fei_ziying) as fei_ziying')->group('time')->order('time desc')->where('store_id = '.$_REQUEST['store_id'])->select();
		}else if($_REQUEST['type'] == 'month'){
			$list = M('store_sale')->field('*,sum(ziying) as ziying_count,sum(fei_ziying) as fei_ziying_count,date_format(time,"%Y-%m") as date')->group('date_format(time,"%Y%m")')->order('time desc')->where('store_id = '.$_REQUEST['store_id'])->select();
		}
		foreach($list as $key => $value){
			if($_REQUEST['type'] == 'day'){
				$begin_time = $value['time'].' 00:00:00';
				$end_time = $value['time'].' 23:59:59';
			}else if($_REQUEST['type'] == 'month'){
				$begin_time = $value['date'].'-01 00:00:00';
				$end_time = $value['date'].'-31 23:59:59';
			}			
			if($_REQUEST['type'] == 'day'){
				$value['price_count'] = number_format($value['ziying'] + $value['fei_ziying'],2);
			}else if($_REQUEST['type'] == 'month'){
				$value['price_count'] = number_format($value['ziying_count'] + $value['fei_ziying_count'],2);
			}
			$value['count'] = M('order')->where('`status` > 6 and add_time >= '.strtotime($begin_time).' and add_time <= '.strtotime($end_time).' and store_id = '.$value['store_id'])->count();
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->assign('store',$store);
		$this->display();
	}
	
	public function food_xiaoliang_ziying(){
		$time_len = count(explode('-',$_REQUEST['time']));
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		if($time_len == 2){	// 月
			$begin_time = strtotime($_REQUEST['time'].'-01 00:00:00');
			$end_time = strtotime($_REQUEST['time'].'-31 23:59:59');
		}else{
			$begin_time = strtotime($_REQUEST['time'].' 00:00:00');
			$end_time = strtotime($_REQUEST['time'].' 23:59:59');
		}
		$order = M('order')->field('id')->where('`status` > 6 and songda_time >= '.$begin_time.' and songda_time <= '.$end_time.' and store_id = '.$_REQUEST['store_id'].' and is_ziying = 1')->select();
		$list = array();
		foreach($order as $key => $value){
			$order_goods = M('order_goods')->where('ziying = 1 and order_id = '.$value['id'])->select();
			foreach($order_goods as $k => $v){
				$v['goods_count'] = number_format($v['goods_number'] * $v['goods_price'],2);
				$list[] = $v;
			}
		}
		$this->assign('store',$store);
		$this->assign('list',$list);
		$this->display();
	}
	
	public function food_liushui_update(){
		$store_id = 85;
		$p = 0;
		$list = M('store_price_log')->where('store_id = '.$store_id)->order('add_time asc')->select();
		foreach($list as $key => $value){
			$order = M('order')->field('pei_price_total')->where('id = '.$value['order_id'])->find();
			if($value['type'] == 0){
				$p += $value['price'];
			}else{
				$p -= $value['price'];
			}
			M('store_price_log')->where('id = '.$value['id'])->save(array('surplus_price' => $p));
		}
	}
	
	public function food_liushui(){
		$list = M('store_price_log')->order('add_time desc')->where('store_id = '.$_REQUEST['store_id'])->select();
		foreach($list as $key => $value){
			$value['action'] = $value['type'] == 0 ? '+' : '-';
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function food_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$find = M('store_user')->where('username = "'.$_REQUEST['username'].'"')->find();
			if($find){
				$this->error('用户名已经存在');
			}else{
				$user['username'] = $_REQUEST['username'];
				$user['password'] = md5($_REQUEST['password']);
				$user['add_time'] = time();
				$user['type'] = 2;
				$user_id = M('store_user')->add($user);
				$data['user_id'] = $user_id;
				$data['school_id'] = $_REQUEST['school_id'];
				$data['type_id'] = $_REQUEST['type_id'];
				$data['category_id'] = $_REQUEST['category_id'];
				$data['is_longyuan'] = $_REQUEST['is_longyuan'];
				$data['sort'] = $_REQUEST['sort'];
				$data['logo'] = $_REQUEST['logo'];
				$data['pei_price'] = empty($_REQUEST['pei_price']) ? '0.00' : $_REQUEST['pei_price'];
				$data['address'] = $_REQUEST['address'];
				$data['store_name'] = $_REQUEST['store_name'];
				$data['desc'] = $_REQUEST['desc'];
				$data['dabao_price'] = empty($_REQUEST['dabao_price']) ? '0.00' : $_REQUEST['dabao_price'];
				$data['price_qisong'] = empty($_REQUEST['price_qisong']) ? '0.00' : $_REQUEST['price_qisong'];
				$data['pei_time'] = $_REQUEST['pei_time'];
				$data['is_chongzhi'] = $_REQUEST['is_chongzhi'];
				$data['time_period'] = $_REQUEST['time_period'];
				$data['is_hezuo'] = $_REQUEST['is_hezuo'];
				$data['dashang_price'] = empty($_REQUEST['dashang_price']) ? '0.00' : $_REQUEST['dashang_price'];
				$data['type'] = 2;
				M('store')->add($data);
				$this->success('添加成功',U('food',array('school_id' => $_REQUEST['menu_school_id'])));
				exit;
			}
		}
		$school = M('school')->order('sort asc')->select();
		$category = M('category')->where('parent_id = 2')->order('sort asc')->select();
		$this->assign('category',$category);
		$this->assign('school',$school);
		$this->display();
	}
	
	public function food_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			if($_REQUEST['username'] == $_REQUEST['old_username']){
				if(!empty($_REQUEST['password'])){
					$user['password'] = md5($_REQUEST['password']);
					M('store_user')->where('id = '.$_REQUEST['user_id'])->save($user);
				}
				$user_id = $_REQUEST['user_id'];
			}else{
				$find = M('store_user')->where('id <> '.$_REQUEST['user_id'].' and username = "'.$_REQUEST['username'].'"')->find();
				if($find){
					$this->error('用户名已经存在');
				}else{
					$user['username'] = $_REQUEST['username'];
					$user['password'] = md5($_REQUEST['password']);
					$user['type'] = 2;
					$user_id = M('store_user')->add($user);
				}
			}
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			$data['type_id'] = $_REQUEST['type_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			$data['sort'] = $_REQUEST['sort'];
			$data['logo'] = $_REQUEST['logo'];
			$data['pei_price'] = empty($_REQUEST['pei_price']) ? '0.00' : $_REQUEST['pei_price'];
			$data['address'] = $_REQUEST['address'];
			$data['store_name'] = $_REQUEST['store_name'];
			$data['is_longyuan'] = $_REQUEST['is_longyuan'];
			$data['desc'] = $_REQUEST['desc'];
			$data['is_chongzhi'] = $_REQUEST['is_chongzhi'];
			$data['time_period'] = $_REQUEST['time_period'];
			$data['is_hezuo'] = $_REQUEST['is_hezuo'];
			$data['price_qisong'] = empty($_REQUEST['price_qisong']) ? '0.00' : $_REQUEST['price_qisong'];
			$data['dabao_price'] = empty($_REQUEST['dabao_price']) ? '0.00' : $_REQUEST['dabao_price'];
			$data['pei_time'] = $_REQUEST['pei_time'];
			$data['dashang_price'] = empty($_REQUEST['dashang_price']) ? '0.00' : $_REQUEST['dashang_price'];
			$data['jiajia'] = empty($_REQUEST['jiajia']) ? '0.00' : $_REQUEST['jiajia'];
			$data['type'] = 2;
			M('store')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('food',array('school_id' => $_REQUEST['menu_school_id'])));
			exit;
		}
		$row = M('store')->where('id = '.$_REQUEST['id'])->find();
		
		$time_period = '';
		if(!empty($row['time_period'])){
			$time_period = explode(',',$row['time_period']);					
			foreach($time_period as $k => $v){
				$v = explode('-',$v);
				$time_period[$k] = $v;
			}
			$row['time_period_arr'] = $time_period;
		}
		$store_user = M('store_user')->where('id = '.$row['user_id'])->find();
		$row['username'] = $store_user['username'];
		$school = M('school')->order('sort asc')->select();
		$category = M('category')->where('parent_id = 2')->order('sort asc')->select();
		$this->assign('category',$category);
		$this->assign('school',$school);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function food_delete(){
		M('store')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('food'));
	}
	
	public function food_commodity(){
		$list = M('store_category')->where('store_id = '.$_REQUEST['store_id'])->order('sort asc')->select();
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->assign('list',$list);
		$this->display();
	}
	
	public function food_commodity_category(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			$data['store_id'] = $_REQUEST['store_id'];
			M('store_category')->add($data);
			$this->success('添加成功',U('food_commodity',array('store_id' => $_REQUEST['store_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->display();
	}
	
	public function food_commodity_category_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			$data['store_id'] = $_REQUEST['store_id'];
			M('store_category')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('food_commodity',array('store_id' => $_REQUEST['store_id'])));
			exit;
		}
		$row = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$this->assign('store',$store);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function food_commodity_category_delete(){
		M('store_category')->where('id = '.$_REQUEST['category_id'])->delete();
		$this->success('删除成功',U('food_commodity',array('store_id' => $_REQUEST['store_id'])));
	}
	
	public function food_commodity_goods(){
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$list = M('store_category_goods')->where('store_id = '.$_REQUEST['store_id'].' and category_id = '.$_REQUEST['category_id'])->order('id desc')->select();
		$this->assign('store',$store);
		$this->assign('list',$list);
		$this->assign('category',$category);
		$this->display();
	}
	
	public function food_commodity_goods_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['goods_name'] = $_REQUEST['goods_name'];
			$data['litpic'] = $_REQUEST['litpic'];
			$data['images'] = $_REQUEST['images'];
			$data['price'] = $_REQUEST['price'];
			$data['youhui'] = $_REQUEST['youhui'];
			$data['zhekou'] = $_REQUEST['zhekou'];
			$data['qianggou_time'] = $_REQUEST['qianggou_time'];
			$data['store_id'] = $_REQUEST['store_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			$data['ziying'] = $_REQUEST['ziying'];
			M('store_category_goods')->add($data);
			$this->success('添加成功',U('food_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$this->assign('store',$store);
		$this->assign('category',$category);
		$this->display();
	}
	
	public function food_commodity_goods_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['goods_name'] = $_REQUEST['goods_name'];
			$data['litpic'] = $_REQUEST['litpic'];
			$data['images'] = $_REQUEST['images'];
			$data['price'] = $_REQUEST['price'];
			$data['ziying'] = $_REQUEST['ziying'];
			$data['store_id'] = $_REQUEST['store_id'];
			$data['category_id'] = $_REQUEST['category_id'];
			$data['youhui'] = $_REQUEST['youhui'];
			$data['zhekou'] = $_REQUEST['zhekou'];
			$data['qianggou_time'] = $_REQUEST['qianggou_time'];
			M('store_category_goods')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('food_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
			exit;
		}
		$store = M('store')->where('id = '.$_REQUEST['store_id'])->find();
		$row = M('store_category_goods')->where('id = '.$_REQUEST['id'])->find();
		$category = M('store_category')->where('id = '.$_REQUEST['category_id'])->find();
		$this->assign('store',$store);
		$this->assign('category',$category);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function food_commodity_goods_delete(){
		M('store_category_goods')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('food_commodity_goods',array('store_id' => $_REQUEST['store_id'],'category_id' => $_REQUEST['category_id'])));
	}
	
	public function xiyi(){
		$list = M('store')->order('sort asc')->where('type = 3')->select();
		foreach($list as $key => $value){
			$school = M('school')->where('id = '.$value['school_id'])->find();
			$value['school'] = $school['name'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function xiyi_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			// 判断用户名是否存在
			$find = M('store_user')->where('username = "'.$_REQUEST['username'].'"')->find();
			if($find) $this->error('用户名已经存在');
			// 判断学校有没有这个商户
			$find = M('store')->where('type = 3 and school_id = '.$_REQUEST['school_id'])->find();
			if($find) $this->error('该学校已存在洗衣商户！');
			$user['username'] = $_REQUEST['username'];
			$user['password'] = md5($_REQUEST['password']);
			$user['add_time'] = time();
			$user['type'] = 3;
			$user_id = M('store_user')->add($user);
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			$data['type'] = 3;
			M('store')->add($data);
			$this->success('添加成功',U('xiyi'));
			exit;
		}
		$school = M('school')->order('sort asc')->select();
		$this->assign('school',$school);
		$this->display();
	}
	
	public function xiyi_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$find = M('store')->where('id <> '.$_REQUEST['id'].' and type = 3 and school_id = '.$_REQUEST['school_id'])->find();
			if($find) $this->error('该学校已存在洗衣商户！');
			if($_REQUEST['username'] == $_REQUEST['old_username']){
				if(!empty($_REQUEST['password'])){
					$user['password'] = md5($_REQUEST['password']);
					M('store_user')->where('id = '.$_REQUEST['user_id'])->save($user);
				}
				$user_id = $_REQUEST['user_id'];
			}else{
				$find = M('store_user')->where('id <> '.$_REQUEST['user_id'].' and username = "'.$_REQUEST['username'].'"')->find();
				if($find){
					$this->error('用户名已经存在');
				}else{
					$user['username'] = $_REQUEST['username'];
					$user['password'] = md5($_REQUEST['password']);
					$user['type'] = 3;
					$user_id = M('store_user')->add($user);
				}
			}
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			M('store')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('xiyi'));
			exit;
		}
		$row = M('store')->where('id = '.$_REQUEST['id'])->find();
		$store_user = M('store_user')->where('id = '.$row['user_id'])->find();
		$row['username'] = $store_user['username'];
		$school = M('school')->order('sort asc')->select();
		$this->assign('school',$school);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function xiyi_delete(){
		M('store')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('xiyi'));
	}
	
	public function kuaidi(){
		$list = M('store')->order('sort asc')->where('type = 4')->select();
		foreach($list as $key => $value){
			$school = M('school')->where('id = '.$value['school_id'])->find();
			$value['school'] = $school['name'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function kuaidi_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			// 判断用户名是否存在
			$find = M('store_user')->where('username = "'.$_REQUEST['username'].'"')->find();
			if($find) $this->error('用户名已经存在');
			// 判断学校有没有这个商户
			$find = M('store')->where('type = 4 and school_id = '.$_REQUEST['school_id'])->find();
			if($find) $this->error('该学校已存在洗衣商户！');
			$user['username'] = $_REQUEST['username'];
			$user['password'] = md5($_REQUEST['password']);
			$user['add_time'] = time();
			$user['type'] = 4;
			$user_id = M('store_user')->add($user);
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			$data['type'] = 4;
			M('store')->add($data);
			$this->success('添加成功',U('kuaidi'));
			exit;
		}
		$school = M('school')->order('sort asc')->select();
		$this->assign('school',$school);
		$this->display();
	}
	
	public function kuaidi_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$find = M('store')->where('id <> '.$_REQUEST['id'].' and type = 4 and school_id = '.$_REQUEST['school_id'])->find();
			if($find) $this->error('该学校已存在洗衣商户！');
			if($_REQUEST['username'] == $_REQUEST['old_username']){
				if(!empty($_REQUEST['password'])){
					$user['password'] = md5($_REQUEST['password']);
					M('store_user')->where('id = '.$_REQUEST['user_id'])->save($user);
				}
				$user_id = $_REQUEST['user_id'];
			}else{
				$find = M('store_user')->where('id <> '.$_REQUEST['user_id'].' and username = "'.$_REQUEST['username'].'"')->find();
				if($find){
					$this->error('用户名已经存在');
				}else{
					$user['username'] = $_REQUEST['username'];
					$user['password'] = md5($_REQUEST['password']);
					$user['type'] = 4;
					$user_id = M('store_user')->add($user);
				}
			}
			$data['user_id'] = $user_id;
			$data['school_id'] = $_REQUEST['school_id'];
			M('store')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('kuaidi'));
			exit;
		}
		$row = M('store')->where('id = '.$_REQUEST['id'])->find();
		$store_user = M('store_user')->where('id = '.$row['user_id'])->find();
		$row['username'] = $store_user['username'];
		$school = M('school')->order('sort asc')->select();
		$this->assign('school',$school);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function kuaidi_delete(){
		M('store')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('kuaidi'));
	}
	
	
	/*充值*/
	public function chongzhi(){
		$money=$_POST['money'];


// error_reporting(0);
		$appkey = 'c86eb87db2fd7713f25d5c5858d1e42b';

		$a = array (
			'appid'=>'wx45fae74da5a3cb9d',
			'mch_id'=>'1349659401',
			'nonce_str'=>getNonceStr(),
			// 'sign'=>'C380BEC2BFD727A4B6845133519F3AD6',
			'body'=>'腾讯充值中心-QQ会员充值',
			'out_trade_no'=>$out_trade_no,
			'total_fee'=>'0.1' * $money,
			'spbill_create_ip'=>'182.201.217.218',
			'notify_url'=>'http://www.weixin.qq.com/wxpay/pay.php',
			'trade_type'=>'NATIVE'
		);

		$a['sign'] = sign($a);
		//$a = http_build_query($a);
		$a = ToXml($a);
		$opts = array(
			'http'=>array(
				'method'=>'POST',
				'header' => 'Content-type:application/xml;encoding=utf-8',
				// 'Content-Length: ' . strlen($a) . 'rn',
				'content' => $a	
			));


		$context = stream_context_create($opts);

		$result = file_get_contents('https://api.mch.weixin.qq.com/pay/unifiedorder',false,$context);

		$result = simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA);

		$result = json_encode($result);

		$result = json_decode($result,true);


		$code_url = $result['code_url'];

// echo $return_code;
		//$this->redirect('index/qrcode.html?url='.11);
		$this->qrcode($code_url);
		 // $this->ajaxReturn($return_code);

		 // exit("$return_code");


    }
	
	public function qrcode($url,$level=3,$size=4)
    {
			
              Vendor('phpqrcode.phpqrcode');
              $errorCorrectionLevel =intval($level) ;//容错级别 
              $matrixPointSize = intval($size);//生成图片大小 
         //   生成二维码图片 
              $object = new \QRcode();
              $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   
	 		echo $return_code;

			exit("$return_code");
    }
	
}