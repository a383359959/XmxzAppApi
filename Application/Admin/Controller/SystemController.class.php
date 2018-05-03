<?php

namespace Admin\Controller;

use Think\Controller;

class SystemController extends Controller{
	
	public function setting(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['value'] = $_REQUEST['time_period'];
			M('setting')->where('name = "time_period"')->save($data);
			$this->success('修改成功！',U('setting'));
			exit;
		}
		$arr = array();
		$list = M('setting')->select();
		foreach($list as $key => $value){
			if($key == 'time_period'){
				$time_period = '';
				if(!empty($value['value'])){
					$time_period = explode(',',$value['value']);					
					foreach($time_period as $k => $v){
						$v = explode('-',$v);
						$time_period[$k] = $v;
					}
				}
				$this->assign('time_period',$value['value']);
				$arr[$value['name']] = $time_period;
			}else{
				$arr[$value['name']] = $value['value'];
			}
		}
		$this->assign('arr',$arr);
		$this->display();
	}
	
	public function xiyitype(){
		$list = M('xiyitype')->order('sort asc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function xiyitype_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['price'] = !$_REQUEST['price'] ? 0 : $_REQUEST['price'];
			$data['sort'] = $_REQUEST['sort'];
			M('xiyitype')->add($data);
			$this->success('添加成功',U('xiyitype'));
			exit;
		}
		$this->display();
	}
	
	public function xiyitype_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['price'] = !$_REQUEST['price'] ? 0 : $_REQUEST['price'];
			$data['sort'] = $_REQUEST['sort'];
			M('xiyitype')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('xiyitype'));
			exit;
		}
		$row = M('xiyitype')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	
	public function xiyitype_del(){
		M('xiyitype')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('xiyitype'));
	}
	
	public function school(){

		$list = M('school')->order('sort asc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function school_delete(){
		M('school')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('school'));
	}
	
	public function school_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			M('school')->add($data);
			$this->success('添加成功',U('school'));
			exit;
		}
		$this->display();
	}
	
	public function school_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			M('school')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('school'));
			exit;
		}
		$row = M('school')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	
	public function school_address(){
		$list = M('school_address')->where('school_id = '.$_REQUEST['id'])->order('sort asc')->select();
		$school = M('school')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('list',$list);
		$this->assign('school',$school);
		$this->display();
	}
	
	public function school_address_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['school_id'] = $_REQUEST['school_id'];
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			M('school_address')->add($data);
			$this->success('添加成功',U('school_address',array('id' => $_REQUEST['school_id'])));
			exit;
		}
		$school = M('school')->where('id = '.$_REQUEST['school_id'])->find();
		$this->assign('school',$school);
		$this->display();
	}
	
	public function school_address_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['name'] = $_REQUEST['name'];
			$data['sort'] = $_REQUEST['sort'];
			M('school_address')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('school_address',array('id' => $_REQUEST['school_id'])));
			exit;
		}
		$row = M('school_address')->where('id = '.$_REQUEST['id'])->find();
		$school = M('school')->where('id = '.$_REQUEST['school_id'])->find();
		$this->assign('school',$school);
		$this->assign('row',$row);
		$this->display();
	}
	
	public function school_address_del(){
		M('school_address')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('school_address',array('id' => $_REQUEST['school_id'])));
	}
	
	public function banner(){
		$list = M('banner')->order('sort asc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function banner_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['imgurl'] = $_REQUEST['imgurl'];
			$data['sort'] = $_REQUEST['sort'];
			M('banner')->add($data);
			$this->success('添加成功',U('banner'));
			exit;
		}
		$this->display();
	}
	
	public function banner_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['imgurl'] = $_REQUEST['imgurl'];
			$data['sort'] = $_REQUEST['sort'];
			M('banner')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('banner'));
			exit;
		}
		$row = M('banner')->where('id = '.$_REQUEST['id'])->find();
		$this->assign('row',$row);
		$this->display();
	}
	
	public function banner_delete(){
		M('banner')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('banner'));
	}
	
	public function user(){
		$count = M('users')->count();
		$page = new \Think\AdminPage($count);
		
		$users_ids = array();
		$nicknames = iconv('gbk','utf-8',$_REQUEST['nickname']);
		if($nicknames != ''){
			$users = M('users')->field('id,user_info')->select();
			foreach($users as $key => $value){
				$user_info = unserialize($value['user_info']);
				$nickname = base64_decode($user_info['nickname']);
				if(strpos($nickname,$nicknames) !== false) $users_ids[] = $value['id'];
			}
		}
		
		$where = !empty($users_ids) && !empty($nicknames) ? 'id in ('.implode(',',$users_ids).')' : '';
		
		$list = M('users')->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		foreach($list as $key => $value){
			$user_info = unserialize($value['user_info']);
			$value['nickname'] = $user_info['nickname'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	
	public function user_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['is_pei'] = $_REQUEST['is_pei'];
			$data['telephone'] = $_REQUEST['telephone'];
			$data['name'] = $_REQUEST['name'];
			M('users')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功',U('user'));
			exit;
		}
		$find = M('users')->where('id = '.$_REQUEST['id'])->find();
		$user_info = unserialize($find['user_info']);
		$find['nickname'] = base64_decode($user_info['nickname']);
		$this->assign('find',$find);
		$this->display();
	}
	
	public function store_tixian(){
		if($_REQUEST['form_submit'] == 'ok'){
			$ids = implode(',',$_REQUEST['ids']);
			$_config = array(
				'detail_data' => $this->format_alipay($_REQUEST['ids']),
				'batch_num' => count($_REQUEST['ids']),
				'batch_fee' => M('store_cash')->where('id in ('.$ids.')')->getField('SUM(price)'),
				'notify_url' => 'http://api.smdouyou.com/store_notify_url.php'
			);
			$this->alipay($_config);
		}
		$list = M('store_cash')->order('add_time desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function format_alipay($ids,$table = 'store_cash'){
		$arr = array();
		foreach($ids as $key => $value){
			$cash = M($table)->where('id = '.$value)->find();
			$arr[] = $cash['id'].'^'.$cash['alipay_accounts'].'^'.$cash['alipay_name'].'^'.$cash['price'].'^提现';
		}
		return implode('|',$arr);
	}
	
	public function alipay($arr){
		$_config = array(
			'_input_charset' => 'UTF-8',
			'service' => 'batch_trans_notify',
			'partner' => '2088521653659439',
			'notify_url' => $arr['notify_url'],
			'account_name' => '沈阳鑫赢福商贸有限公司',
			'detail_data' => $arr['detail_data'],
			'batch_no' => date('YmdHis'),
			'batch_num' => $arr['batch_num'],
			'batch_fee' => $arr['batch_fee'],
			'email' => 'jiyang10301@126.com',
			'pay_date' => date('Ymd'),
		);		
		$_config['sign'] = $this->alipay_sign($_config);
		$_config['sign_type'] = 'MD5';
		$url = 'https://mapi.alipay.com/gateway.do?';
		$options = array();
		foreach($_config as $key => $value){
			$options[] = $key.'='.$value;
		}
		$url .= implode('&',$options);
		header('location:'.$url);
	}
	
	public function alipay_sign($arr){
		ksort($arr);
		reset($arr);
		$arg  = '';
		while(list($key,$value) = each($arr)){
			$arg .= $key.'='.$value.'&';
		}
		$arg = substr($arg,0,count($arg) - 2);
		if(get_magic_quotes_gpc()) $arg = stripslashes($arg);
		$arg .= 'psdnc99wuxvwsc025a5x5wbvq3ku7rjo';
		return md5($arg);
	}
	
	public function tixian_act(){
		$data['status'] = $_REQUEST['status'];
		M('users_tixian')->where('id = '.$_REQUEST['id'])->save($data);
		$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	public function order(){
		$where = array();
		if($_REQUEST['peisong_id'] != '') $where[] = 'a.peisong_id = '.$_REQUEST['peisong_id'];
		if($_REQUEST['school_id'] != '') $where[] = 'a.school_id = '.$_REQUEST['school_id'];
		if($_REQUEST['store_id'] != '') $where[] = 'a.store_id = '.$_REQUEST['store_id'];
		if($_REQUEST['user_id'] != '') $where[] = 'a.user_id = '.$_REQUEST['user_id'];
		if($_REQUEST['begin_time'] != '') $where[] = 'a.pay_time >= '.strtotime($_REQUEST['begin_time']);
		if($_REQUEST['end_time'] != '') $where[] = 'a.pay_time <= '.strtotime($_REQUEST['end_time']);
		if($_REQUEST['where'] != '') $where[] = $_REQUEST['where'];
		
		$users_address_ids = array();
		if(!empty($_REQUEST['keyword'])){
			$address_list = M('users_address')->field('id')->where('telephone like "%'.$_REQUEST['keyword'].'%" OR name like "%'.$_REQUEST['keyword'].'%"')->select();
			foreach($address_list as $key => $value){
				$users_address_ids[] = $value['id'];
			}
		}
		if(!empty($users_address_ids)) $where[] = 'b.users_address_id in ('.implode(',',$users_address_ids).')';
		
		$count = M('order as a')->where($where)->count();
		$page = new \Think\AdminPage($count);
		
		$list = M('order as a')->field('a.*,b.users_address_id')->where($where)->JOIN('__ORDER_FOOD__ as b ON a.foreign_key = b.id','LEFT')->order('pay_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		// echo M('order as a')->_sql();
		$goods_price = 0;
		$dashang_price = 0;
		
		// die('asd');
		
		foreach($list as $key => $value){
			$value['child'] = M($value['table'])->where('id = '.$value['foreign_key'])->find();
			$value['goods_price_count'] = getOrderZhenPirce($value['id']);
			$value['store'] = M('store')->where('id = '.$value['store_id'])->find();
			$value['user_address'] = getAddress($value['id']);
			if($value['peisong_id'] > 0) $value['peisong'] = M('peisong')->where('id = '.$value['peisong_id'])->find();
			$goods_price += $value['goods_price_count'];
			$dashang_price += $value['child']['dashang_price'];
			$list[$key] = $value;
		}
		
		if(!empty($_REQUEST['school_id'])){
			$peisong = M('peisong')->where('work_status = 0 and school_id = '.$_REQUEST['school_id'])->order('id desc')->select();
		}else{
			$peisong = M('peisong')->where('work_status = 0')->order('id desc')->select();
		}

		$this->assign('peisong',$peisong);
		$this->assign('goods_price',$goods_price);
		
		$this->assign('dashang_price',$dashang_price);
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	
	public function order_ajax_tongji(){
		$a = getOrderShangPinCount();
		$a = str_replace(',','',$a);
		$b = getOrderDaShangCount();
		$b = str_replace(',','',$b);
		$total = number_format($a + $b,2);
		$html = '
			<p>订单数量<span>'.getOrderCount().'</span>单，完成订单数量<span>'.getOrderYiWanChengCount().'</span>单，下订单但是未付款数量<span>'.getOrderXiaDanCount().'</span>单，退单数量<span>'.getOrderTuiDanCount().'</span>单，正在配送中订单数量<span>'.getOrderPeiSongCount().'</span>单</p>
			<p>总金额<span>'.$total.'</span>元，商品金额<span>'.getOrderShangPinCount().'</span>元，打赏费<span>'.getOrderDaShangCount().'</span>元</p>
		';
		die($html);
	}
	
	public function order_detail(){
		$order = M('order')->where('id = '.$_REQUEST['order_id'])->find();
		if($order['peisong_id'] > 0) $order['peisong'] = M('peisong')->where('id = '.$order['peisong_id'])->find();
		$order_msg = M('order_msg')->where('order_id = '.$_REQUEST['order_id'])->order('id desc')->select();
		$this->assign('order_msg',$order_msg);
		$this->assign('address',getAddress($_REQUEST['order_id']));
		$store = M('store')->where('id = '.$order['store_id'])->find();
		$order['store_name'] = $store['store_name'];
		$this->assign('order',$order);
		$this->display();
	}
	
	public function order_paidan(){
		$order = M('order')->where('id = '.$_REQUEST['order_id'])->find();
		if($order['status'] == 1 || $_REQUEST['action'] == 'paidan'){
			$peisong = M('peisong')->where('id = '.$_REQUEST['peisong_id'])->find();
			$order['openid'] = $peisong['openid'];
			$data['status'] = 5;
			$data['is_qucan'] = 0;
			$data['peisong_id'] = $_REQUEST['peisong_id'];
			$data['edit_time'] = time();
			M('order')->where('id = '.$_REQUEST['order_id'])->save($data);
			if(!empty($peisong['openid'])) $this->wxPush($order);
			if($peisong['clientid'] != ''){
				Vendor('Push.Push');
				$_config = array(
					'peisong_id' => $peisong['id'],
				);
				$push = new \Push($_config);
				$push->pushMessageToSingle();
			}
			die(json_encode(array('status' => 'success')));
		}else{
			die(json_encode(array('status' => 'error')));
		}
	}	
	
	function wxPush($order){
		$address = getAddress($order['id']);
		$msg = array(
			'first' => array(
				'value' => '您有新的订单消息',
				'color' => '#333'
			),
			'tradeDateTime' => array(
				'value' => date('Y-m-d H:i:s',time()),
				'color' => '#333'
			),
			'orderType' => array(
				'value' => '已派单',
				'color' => '#333'
			),
			'customerInfo' => array(
				'value' => $address['name'],
				'color' => '#333'
			),
			'orderItemName' => array(
				'value' => '订单编号',
				'color' => '#333'
			),
			'orderItemData' => array(
				'value' => $order['order_sn'],
				'color' => '#333'
			),

		);
		$msg['remark'] = array(
			'value' => '订单状态：已派单到骑手手中\n联系方式：'.$address['telephone'].'\n收货地址：'.$address['address'],
			'color' => '#333'
		);
		$weixin = new \Think\WeiXinTemplate();
		$weixin->send($order['openid'],$msg,$order['id']);
	}
	
	public function order_tuikuan(){
		$order = M('order')->where('id = '.$_REQUEST['id'])->find();
		if($order['status'] == 2 || $order['status'] == 3) $this->error('该订单已经退款过，无需退款');
		$store = M('store')->field('id,price,category_id,is_kou')->where('id = '.$order['store_id'])->find();
		if($store['category_id'] == 1 && $store['is_chongzhi'] == 0){	// 封闭式
			M('store')->where('id = '.$store['id'])->setInc('price',1);
			$data = array(
				'store_id' => $store['id'],
				'add_time' => time(),
				'price' => 1,
				'order_id' => $order['id'],
				'type' => 1,
				'surplus_price' => ($store['price'] + 1),
				'desc' => '退款'
			);
			M('store_price_log')->add($data);
		}else if($store['category_id'] == 2){	// 开放式
			$p = $store['is_kou'] == 0 ? $order['pei_price_total'] - 1 : $order['pei_price_total'];
			M('store')->where('id = '.$store['id'])->setDec('price',$p);
			$data = array(
				'store_id' => $store['id'],
				'add_time' => time(),
				'price' => 1,
				'order_id' => $order['id'],
				'type' => 1,
				'surplus_price' => ($p),
				'desc' => '退款'
			);
			M('store_price_log')->add($data);
		}
		M('order')->where('id = '.$order['id'])->save(array('status' => 2));
		setOrderStatus($order['id'],'商家已取消');
		M('users')->where('id = '.$order['user_id'])->setInc('money',$order['pay_price_total']);
		$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	public function order_queren(){
		$order = M('order')->where('id = '.$_REQUEST['id'])->find();
		if($order['shipping_status'] == 1) $this->error('该订单已经确认过，无需退款');
		if($order['tuikuan'] == 1) $this->error('该订单已经退款过，不能确认');
		$orderMsg = explode(',',$order['msg_status']);
		$orderMsg[] = '订单已完成';
		$times = explode(',',$order['times']);
		$times[] = time();
		$data['msg_status'] = implode(',',$orderMsg);
		$data['times'] = implode(',',$times);
		$data['pay_status'] = 1;
		$data['shipping_status'] = 1;
		M('order')->where('id = '.$_REQUEST['id'])->save($data);
		M('users')->where('id = '.$order['pei_user'])->setInc('money',$_REQUEST['price']);
		$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	public function activity(){
		$list = M('store_activity')->order('id desc')->select();
		foreach($list as $key => $value){
			$store = M('store')->field('store_name,school_id')->where('id = '.$value['store_id'])->find();
			$value['store_name'] = $store['store_name'];
			$value['school_name'] = M('school')->where('id = '.$store['school_id'])->getField('name');
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function activity_status(){
		$data['status'] = $_REQUEST['status'];
		$data['status'] == 2 ? $data['success_time'] = time() : $data['error_time'] = time();
		M('store_activity')->where('id = '.$_REQUEST['id'])->save($data);
		$this->success('改变状态成功！',U('activity'));
	}
	
	public function peisong(){
		$where[] = '1 = 1';
		if(!empty($_REQUEST['school_id'])) $where[] = 'school_id = '.$_REQUEST['school_id'];
		$list = M('peisong')->order('id desc')->where(implode(' and ',$where))->select();
		foreach($list as $key => $value){
			$begin_time = date('Y-m-d 0:0:0');
			$end_time = date('Y-m-d 23:59:59');
			$value['order_count'] = M('order')->where('songda_time >= '.strtotime($begin_time).' and songda_time <= '.strtotime($end_time).' and peisong_id = '.$value['id'])->getField('count(*)');
			$value['yes_order_count'] = M('order')->where('songda_time >= '.(strtotime($begin_time) - 86400).' and songda_time <= '.(strtotime($end_time) - 86400).' and peisong_id = '.$value['id'])->getField('count(*)');
			
			$school = M('school')->where('id = '.$value['school_id'])->find();
			$user = M('store_user')->where('id = '.$value['user_id'])->find();
			$value['school_name'] = $school['name'];
			$value['username'] = $user['username'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function peisong_edit(){
		if($_REQUEST['form_submit'] == 'ok'){
			$user = M('store_user')->where('username = "'.$_REQUEST['username'].'" and id <> '.$_REQUEST['user_id'])->find();
			if($user) $this->error('用户名已存在！');
			$user_arr['username'] = $_REQUEST['username'];
			if(!empty($_REQUEST['password'])) $user_arr['password'] = md5($_REQUEST['password']);
			M('store_user')->where('id = '.$_REQUEST['user_id'])->save($user_arr);
			$data = array(
				'school_id' => $_REQUEST['school_id'],
				'shenfen' => $_REQUEST['shenfen'],
				'name' => $_REQUEST['name'],
				'phone' => $_REQUEST['phone'],
				'status' => 1
			);
			M('peisong')->where('id = '.$_REQUEST['id'])->save($data);
			$this->success('修改成功！',U('peisong'));
			exit;
		}
		$peisong = M('peisong')->where('id = '.$_REQUEST['id'])->find();
		$peisong['user'] = M('store_user')->where('id = '.$peisong['user_id'])->find();
		$this->assign('peisong',$peisong);
		$this->assign('school',M('school')->order('sort asc')->select());
		$this->display();
	}
	
	public function peisong_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$user = M('store_user')->where('username = "'.$_REQUEST['username'].'"')->find();
			if($user) $this->error('用户名已存在！');
			$user_arr['username'] = $_REQUEST['username'];
			$user_arr['password'] = md5($_REQUEST['password']);
			$user_arr['type'] = 4;
			$user_id = M('store_user')->where('id = '.$_REQUEST['user_id'])->add($user_arr);
			$data = array(
				'user_id' => $user_id,
				'school_id' => $_REQUEST['school_id'],
				'shenfen' => $_REQUEST['shenfen'],
				'name' => $_REQUEST['name'],
				'phone' => $_REQUEST['phone'],
				'add_time' => time(),
				'status' => 1
			);
			M('peisong')->add($data);
			$this->success('添加成功！',U('peisong'));
			exit;
		}
		$this->assign('school',M('school')->order('sort asc')->select());
		$this->display();
	}
	
	public function peisong_msg(){
		$where[] = 'peisong_id = '.$_REQUEST['id'];
		if($_REQUEST['level'] > 0) $where[] = 'lv = '.$_REQUEST['level'];
		if(!empty($_REQUEST['begin_time'])) $where[] = 'add_time >= '.strtotime($_REQUEST['begin_time']);
		if(!empty($_REQUEST['end_time'])) $where[] = 'add_time <= '.strtotime($_REQUEST['end_time']);
		$list = M('peisong_eval')->where(implode(' AND ',$where))->order('id desc')->select();
		foreach($list as $key => $value){
			$user = M('users')->where('id = '.$value['user_id'])->find();
			$user = unserialize($user['user_info']);
			$value['nickname'] = base64_decode($user['nickname']);
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function asd(){
		
	}
	
	public function peisong_msg_content(){
		$row = M('peisong_eval')->where('id = '.$_REQUEST['id'])->find();
		$row['order'] = M('order')->where('id = '.$row['order_id'])->find();
		$row['store'] = M('store')->where('id = '.$row['order']['store_id'])->find();
		$row['child'] = M($row['order']['table'])->where('id = '.$row['order']['foreign_key'])->find();
		$this->assign('row',$row);
		$this->assign('address',getAddress($row['order']['id']));
		$this->display();
	}
	
	public function peisong_del(){
		$peisong = M('peisong')->where('id = '.$_REQUEST['id'])->find();
		M('store_user')->where('id = '.$peisong['user_id'])->delete();
		M('peisong')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	public function store_eval(){
		$list = M('store_eval')->order('id desc')->select();
		foreach($list as $key => $value){
			$value['store_name'] = M('store')->where('id = '.$value['store_id'])->getField('store_name');
			$user = M('users')->where('id = '.$value['user_id'])->find();
			$user = unserialize($user['user_info']);
			$value['nickname'] = base64_decode($user['nickname']);
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function store_eval_content(){
		$row = M('store_eval')->where('id = '.$_REQUEST['id'])->find();
		$row['order'] = M('order')->where('id = '.$row['order_id'])->find();
		$row['store'] = M('store')->where('id = '.$row['order']['store_id'])->find();
		$row['child'] = M($row['order']['table'])->where('id = '.$row['order']['foreign_key'])->find();
		$this->assign('row',$row);
		$this->assign('address',getAddress($row['order']['id']));
		$this->display();
	}
	
	public function map(){
		$school = M('school')->where('id = '.$_REQUEST['school_id'])->find();
		$peisong = M('peisong')->where('work_status = 0 and school_id = '.$_REQUEST['school_id'])->order('id desc')->select();
		$this->assign('peisong',$peisong);
		$this->assign('school',$school);
		$this->display();
	}
	
	public function map_data(){
		$list = M('peisong')->where('school_id = '.$_REQUEST['school_id'].' and work_status = 0 and lng != "" and lat != ""')->select();
		foreach($list as $key => $value){
			// $peisong_position_time = M('peisong_position')->where('peisong_id = '.$value['id'])->order('id desc')->getField('add_time');
			// $value['peisong_time'] = date('Y-m-d H:i:s',$peisong_position_time);
			$value['yiqu'] = M('order')->where('peisong_id = '.$value['id'].' and `status` = 6 and is_qucan = 1')->getField('count(*)');
			$value['weiqu'] = M('order')->where('peisong_id = '.$value['id'].' and `status` = 6 and is_qucan = 0')->getField('count(*)');
			$value['weijie'] = M('order')->where('peisong_id = '.$value['id'].' and `status` = 5')->getField('count(*)');
			$value['latitude'] = $value['lat'];
			$value['longitude'] = $value['lng'];
			$begin_time = date('Y-m-d 0:0:0');
			$end_time = date('Y-m-d 23:59:59');
			$value['order_count'] = M('order')->where('songda_time >= '.strtotime($begin_time).' and songda_time <= '.strtotime($end_time).' and peisong_id = '.$value['id'])->getField('count(*)');
			/*
			if($value['work_status'] == 0){
				$value['work_status'] = '开工';
			}else if($value['work_status'] == 1){
				$value['work_status'] = '小休';
			}else if($value['work_status'] == 2){
				$value['work_status'] = '收工';
			}
			*/
			$list[$key] = $value;
		}
		$order = M('order')->where('school_id = '.$_REQUEST['school_id'].' and `pay_status` = 1 and `status` = 1')->order('add_time asc')->select();
		$html = '';
		foreach($order as $key => $value){
			$store = M('store')->where('id = '.$value['store_id'])->find();
			$table = M($value['table'])->where('id = '.$value['foreign_key'])->find();
			$user_address = getAddress($value['id']);
			$html .= '
				<li>
					<p>商户名称：'.$store['store_name'].($table['pei_time'] == 1 ? '' : '<span style="color:red;">（预定）</span>').'</p>
					<p>商户地址：'.$store['address'].'</p>
					<p>订单编号：'.$value['order_sn'].'</p>
					<p>下单时间：'.date('Y-m-d H:i:s',$value['add_time']).'</p>
					<p>配送时间：'.($table['pei_time'] == 1 ? '尽快送达' : date('Y-m-d H:i:s')).'</p>
					<p>送货地址：'.$user_address['address'].'</p>
					<div class="order_manager_action">
						<a href="'.U('order_detail',array('order_id' => $value['id'])).'" target="_blank">查看</a>
						<a href="javascript:;" class="paidan" order_id="'.$value['id'].'">派单</a>
						<div class="clear"></div>
					</div>
				</li>
			';
		}
		$arr['html'] = $html;
		$arr['peisong'] = $list;
		die(json_encode($arr));
	}
	
	public function peisong_order(){
		$peisong = M('peisong')->where('id = '.$_REQUEST['peisong_id'])->find();
		$order = M('order')->where('(`status` = 5 OR `status` = 6) and peisong_id = '.$_REQUEST['peisong_id'])->order('id desc')->select();
		$html = '';
		foreach($order as $key => $value){
			$store = M('store')->where('id = '.$value['store_id'])->find();
			$table = M($value['table'])->where('id = '.$value['foreign_key'])->find();
			$user_address = getAddress($value['id']);
			$order_status = '';
			if($value['status'] == 5){
				$order_status = '未接单';
			}else if($value['status'] == 6 && $value['is_qucan'] == 0){
				$order_status = '未取餐';
			}else if($value['status'] == 6 && $value['is_qucan'] == 1){
				$order_status = '已取餐';
			}
			$html .= '
				<tr>
					<td width="23%">'.$store['store_name'].'</td>
					<td width="25%">'.$user_address['address'].'</td>
					<td width="15%">'.($table['pei_time'] == 1 ? '尽快送达' : date('Y-m-d H:i',$table['pei_time'])).'</td>
					<td width="10%">'.$order_status.'</td>
					<td width="17%">'.date('Y-m-d H:i:s',$value['edit_time']).'</td>
					<td width="10%"><a href="'.U('order_detail',array('order_id' => $value['id'])).'" target="_blank">详细</a>&nbsp;|&nbsp;<a href="javascript:;" class="paidan" order_id="'.$value['id'].'" action="paidan">改派</a></td>
				</tr>
			';
		}
		$arr['html'] = $html;
		$arr['peisong'] = $peisong;
		die(json_encode($arr));
	}
	
	public function push(){
		Vendor('Push.Push');
		$_config = array(
			'peisong_id' => 20,
		);
		$push = new \Push($_config);
		$push->pushMessageToSingle();
	}
	
	public function peisong_version(){
		$list = M('version')->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function peisong_version_add(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['version'] = $_REQUEST['version'];
			$data['file'] = $_REQUEST['file'];
			$data['add_time'] = time();
			M('version')->add($data);
			$this->success('添加成功！',U('peisong_version'));
			exit;
		}
		$this->display();
	}
	
	public function longyuan(){
		$list = M('store')->where('is_longyuan = 1')->order('sort asc')->select();
		$today = 0;
		$yestoday = 0;
		foreach($list as $key => $value){
			$begin_time = date('Y-m-d').' 00:00:00';
			$end_time = date('Y-m-d').' 23:59:59';
			$value['today'] = M('order')->where('pay_status = 1 and `status` > 6 and store_id = '.$value['id'].' and add_time >= '.strtotime($begin_time).' and add_time <= '.strtotime($end_time))->getField('SUM(pei_price_total)');
			$today += $value['today'];
			$value['yestoday'] = M('order')->where('pay_status = 1 and `status` > 6 and store_id = '.$value['id'].' and add_time >= '.(strtotime($begin_time) - 86400).' and add_time <= '.(strtotime($end_time) - 86400))->getField('SUM(pei_price_total)');
			$yestoday += $value['yestoday'];
			$list[$key] = $value;
		}
		$this->assign('list',$list);
		$this->assign('today',number_format($today,2));
		$this->assign('yestoday',number_format($yestoday,2));
		$this->display();
	}
	
	public function order_xiaoshou(){
		$where = array();
		if(!empty($_REQUEST['school_id'])) $where = 'school_id = '.$school_id;
		
		$arr = array();
		$arr[] = getOrderCount();
		$arr[] = getOrderTuiDanCount();
		$arr[] = getPeiSongHaoPing();
		$arr[] = getPeiSongChaPing();
		$arr[] = getShangHuHaoPing();
		$arr[] = getShangHuChaPing();
		$arr[] = getTouSu();
		$arr[] = getOrderPriceCount();
		$arr[] = getOrderDaShangCount();
		$arr[] = getOrderShangPinCount();
		$arr[] = getOrderUserCount();
		
		$this->assign('data',json_encode($arr));
		$this->display();
	}
	
	public function time(){
		if($_REQUEST['form_submit'] == 'ok'){
			$data['time_period'] = $_REQUEST['time_period'];
			M('school')->where('id = '.$_REQUEST['school_id'])->save($data);
			$this->success('修改成功！',U('school'));
			exit;
		}
		
		$time_period_string = M('school')->where('id = '.$_REQUEST['school_id'])->getField('time_period');
		if(!empty($time_period_string)){
			$time_period_arr = explode(',',$time_period_string);					
			foreach($time_period_arr as $k => $v){
				$v = explode('-',$v);
				$time_period_arr[$k] = $v;
			}
		}
		$arr['time_period'] = $time_period_arr;
		$this->assign('arr',$arr);
		$this->assign('time_period',$time_period_string);
		$this->assign('school_name',M('school')->where('id = '.$_REQUEST['school_id'])->getField('name'));
		$this->display();
	}
	
	public function tousu(){
		$list = M('tousu')->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
}