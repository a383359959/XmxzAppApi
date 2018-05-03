<?php
function adminTitle($data){
	$data = explode('/',$data);
	$path = dirname(__FILE__).'/menu.php';
	$menu = include($path);
	return $menu[$data[0]][$data[1]].' - '.C('ADMIN_COPYRIGHT');
}

function createUploads($name,$value = ''){
	$images = '';
	if(!empty($value)){
		$p = explode(',',$value);
		foreach($p as $key => $value){
			$images .= '<li class="images_value"><img src="'.$value.'"></li>';
		}
	}
	$html = '
		<div class="uploads">
			<input type="hidden" name="'.$name.'" value="'.$value.'" />
			<ul>
				'.$images.'
				<li class="'.$name.'_add"><img src="/Public/Admin/images/upload.jpg"></li>
			</ul>
		</div>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				var editor = K.editor({
					allowFileManager : true
				});
				K(\'.'.$name.'_add\').click(function() {
					editor.loadPlugin(\'multiimage\', function() {
						editor.plugin.multiImageDialog({
							clickFn : function(urlList) {
								var html = \'\';
								K.each(urlList, function(i, data){
									html += \'<li class="'.$name.'_value"><img src="\' + data.url + \'"></li>\';
								});
								$(\'.'.$name.'_add\').before(html);
								var string = \'\';
								$(\'.'.$name.'_value\').each(function(){
									var src = $(this).find(\'img\').attr(\'src\');
									if(string == \'\'){
										string = src;
									}else{
										string += \',\' + src;
									}
								});
								$(\'input[name="'.$name.'"]\').val(string);
								editor.hideDialog();
							}
						});
					});
				});
			});
		</script>
	';
	return $html;
}

function createUpload($name,$value = ''){
	$img = $value == '' ? '<img src="/Public/Admin/images/upload.jpg" />' : '<img src="'.$value.'" />';
	$html = '
		<div class="upload '.$name.'">
			<input type="hidden" name="'.$name.'" value="'.$value.'" />
			'.$img.'
		</div>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				var editor = K.editor({
					allowFileManager : true
				});
				K(\'.'.$name.'\').click(function() {
					editor.loadPlugin(\'image\', function() {
						editor.plugin.imageDialog({
							showRemote : false,
							imageUrl : K(\'input[name="'.$name.'"]\').val(),
							clickFn : function(url, title, width, height, border, align) {
								K(\'input[name="'.$name.'"]\').val(url);
								K(\'.'.$name.' img\').attr(\'src\',url);
								editor.hideDialog();
							}
						});
					});
				});
			});
	
		</script>
	';
	return $html;
}


function createFile($name,$value = ''){
	$img = $value == '' ? '<img src="/Public/Admin/images/upload.jpg" />' : '<img src="/Public/Admin/images/upload_success.png" />';
	$html .= '
		<div class="upload '.$name.'">
			<input type="hidden" name="'.$name.'" value="'.$value.'" />
			'.$img.'
		</div>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				var editor = K.editor({
					allowFileManager : false
				});
				K(\'.'.$name.'\').click(function() {
					editor.loadPlugin(\'insertfile\', function() {
						editor.plugin.fileDialog({
							fileUrl : K(\'#url\').val(),
							clickFn : function(url, title) {
								K(\'input[name="'.$name.'"]\').val(url);
								K(\'.'.$name.' img\').attr(\'src\',\'/Public/Admin/images/upload_success.png\');
								editor.hideDialog();
							}
						});
					});
				});
			});
		</script>
	';
	return $html;
}

function createEditor($name,$value = ''){
	$html = '
		<textarea name="'.$name.'">'.$value.'</textarea>
		<script>
			KindEditor.ready(function() {
				var editor = KindEditor.create(\'textarea[name="'.$name.'"]\', {
					allowFileManager : true
				});
			});
		</script>
	';
	return $html;
}

function getOrderStatus($order_id){
	$find = M('order')->where('id = '.$order_id)->find();
	$arr = array();
	if($find['pay_status'] == 0){
		$arr[] = array('msg' => '未付款','time' => $find['add_time']);
	}else{
		$msg_status = explode(',',$find['msg_status']);
		$times = explode(',',$find['times']);
		foreach($msg_status as $key => $value){
			$time = !empty($times[$key]) ? $times[$key] : $find['add_time'];
			$arr[] = array('msg' => $value,'time' => $time);
		}
	}
	return $arr;
}
/*
function getOrderGoods($order){
	$list = M('order_goods')->where('order_id = '.$order['id'])->select();
	$tr = '';
	$item = '';
	$count = $order['goods_price'];
	if($order['pei_price'] > 0){	// 如果有配送费
		$item .= '
			<tr height="40">
				<td>配送费</td>
				<td align="right" width="25%"></td>
				<td align="right" width="25%">￥'.$order['pei_price'].'</td>
			</tr>
		';
		$count += $order['pei_price'];
	}
	if($order['dabao_price'] > 0){	// 如果有打包费
		$item .= '
			<tr height="40">
				<td>打包费</td>
				<td align="right" width="25%"></td>
				<td align="right" width="25%">￥'.$order['dabao_price'].'</td>
			</tr>
		';
		$count += $order['dabao_price'];
	}
	if($order['dashang_price'] > 0){	// 如果有打赏费
		$item .= '
			<tr height="40">
				<td>打赏费</td>
				<td align="right" width="25%"></td>
				<td align="right" width="25%">￥'.$order['dashang_price'].'</td>
			</tr>
		';
		$count += $order['dashang_price'];
	}
	$item .= '
		<tr height="40">
			<td>合计</td>
			<td align="right" width="25%"></td>
			<td align="right" width="25%">￥'.number_format($count,2).'</td>
		</tr>
	';
	foreach($list as $key => $value){
		$tr .= '
			<tr height="40">
				<td>'.$value['goods_name'].'</td>
				<td width="25%" align="right">x'.$value['goods_number'].'</td>
				<td width="25%" align="right">￥'.$value['goods_price'].'</td>
			</tr>
		';
	}
	$html = '<table width="100%" cellpadding="0" cellspacing="0">'.$tr.$item.'</table>';
	return $html;
}*/
function getOrderGoods($order){
	$order = M('order')->where('id = '.$order['id'])->find();
	$table = M($order['table'])->where('id = '.$order['foreign_key'])->find();
	$order_goods = M('order_goods')->where('order_id = '.$order['id'])->select();
	$tr = '';
	$yprice = 0;	// 优惠的钱
	$goods_price = 0;	// 优惠的钱
	foreach($order_goods as $key => $value){
		if($value['activity_id']){
			$store_activity = M('store_activity')->field('activity_id')->where('id = '.$value['activity_id'])->find();
			$activity = M('activity')->field('title')->where('id = '.$store_activity['activity_id'])->find();
			switch($activity['title']){
				case '天天特价':
					$p = $value['goods_price'] * $value['goods_number'] - $value['price_activity'] * $value['goods_number'];
					$yprice += $p;
					break;
				case '限时抢购':
					$p = $value['goods_price'] * $value['goods_number'] - $value['price_activity'] * $value['goods_number'];
					$yprice += $p;
					break;
				case '买一赠一':
					if($value['goods_number'] > 1){
						$n = floor($value['goods_number'] / 2);
						$yprice += $value['goods_price'] * $n;
					}
					break;
				case '第二杯半价':
					if($value['goods_number'] > 1){
						$n = floor($value['goods_number'] / 2);
						$yprice += $value['goods_price'] / 2 * $n;
					}
					break;
			}
		}
		$tr .= '
			<tr height="40">
				<td>'.$value['goods_name'].'</td>
				<td width="25%" align="right">x'.$value['goods_number'].'</td>
				<td width="25%" align="right">￥'.($value['goods_price'] * $value['goods_number']).'</td>
			</tr>
		';
		$goods_price += $value['goods_number'] * $value['goods_price'];
	}
	if($yprice > 0){
		$tr .= '
			<tr height="40">
				<td>优惠</td>
				<td align="right" width="25%"></td>
				<td align="right" width="25%">￥-'.$yprice.'</td>
			</tr>
		';
		$price -= $yprice;
	}
	$tr .= '
		<tr height="40">
			<td>配送费</td>
			<td align="right" width="25%"></td>
			<td align="right" width="25%">￥'.$table['pei_price'].'</td>
		</tr>
	';
	$tr .= '
		<tr height="40">
			<td>打包费</td>
			<td align="right" width="25%"></td>
			<td align="right" width="25%">￥'.$table['dabao_price'].'</td>
		</tr>
	';
	$tr .= '
		<tr height="40">
			<td>打赏费</td>
			<td align="right" width="25%"></td>
			<td align="right" width="25%">￥'.$table['dashang_price'].'</td>
		</tr>
	';
	$tr .= '
		<tr height="40">
			<td>合计</td>
			<td align="right" width="25%"></td>
			<td align="right" width="25%">￥'.($goods_price + $table['dashang_price'] - $yprice).'</td>
		</tr>
	';
	$html = '<table width="100%" cellpadding="0" cellspacing="0">'.$tr.'</table>';
	return $html;
}

function setOrderStatus($order_id,$msg){
	$data['order_id'] = $order_id;
	$data['msg'] = $msg;
	$data['time'] = time();
	return M('order_msg')->add($data);
}

/*
*	获取订单地址
*/
function getAddress($order_id){
	$order = M('order')->where('id = '.$order_id)->find();
	$table = M($order['table'])->where('id = '.$order['foreign_key'])->find();
	$school_address = M('school_address')->where('id = '.$order['school_address_id'])->find();
	$school_name = M('school')->where('id = '.$school_address['school_id'])->getField('name');
	$address = $school_name.' '.$school_address['name'].' ';
	$arr = array();
	if($order['type'] == 1){	// 洗衣
	
	}else if($order['type'] == 2){
		
	}else if($order['type'] == 3){
		$users_address = M('users_address')->where('id = '.$table['users_address_id'])->find();
		$arr['name'] = $users_address['name'];
		$arr['telephone'] = $users_address['telephone'];
		$arr['address'] = $address.$users_address['address'];
	}else if($order['type'] == 4){
		
	}
	return $arr;
}

function getOrderPrice($order_id,$type){
	$price = 0;
	$order = M('order')->field('table,foreign_key,pei_type')->where('id = '.$order_id)->find();
	$list = M('order_goods')->field('ziying,goods_price,goods_number')->where('order_id = '.$order_id)->select();
	foreach($list as $key => $value){
		if($value['ziying'] == $type) $price += $value['goods_price'] * $value['goods_number'];
	}
	if($order['pei_type'] == 2 && $type == 0){
		$pei_price = M($order['table'])->field('pei_price')->where('id = '.$order['foreign_key'])->getField('pei_price');
		$price += $pei_price;
	}
	return number_format($price,2);
}

function getPrice($store_id,$type = 0,$time){
	$price = 0;
	$begin_time = $time.' 00:00:00';
	$end_time = $time.' 23:59:59';
	$order = M('order')->field('pei_type,id')->where('store_id = '.$store_id.' and `status` > 6 and add_time >= '.strtotime($begin_time).' and add_time <= '.strtotime($end_time))->select();
	foreach($order as $key => $value){
		$p = 0;
		$order_goods = M('order_goods')->field('ziying,goods_price,goods_number')->where('order_id = '.$value['id'])->select();
		foreach($order_goods as $k => $v){
			if($v['ziying'] == $type) $p += $v['goods_price'] * $v['goods_number'];
		}
		if($value['pei_type'] == 2 && $type == 0){
			$pei_price = M($order['table'])->field('pei_price')->where('id = '.$value['foreign_key'])->getField('pei_price');
			$p += $pei_price;
		}
		$price += $p;
	}
	return number_format($price,2);
}

function getOrderZhenPirce($order_id){
	$t1 = M('order_goods as a')->field('a.activity_id,a.price_activity,a.goods_number,a.goods_price')->join('__STORE_ACTIVITY__ as b on a.activity_id = b.id')->where('a.order_id = '.$order_id)->getField('a.price_activity * a.goods_number');
	/*
	$list = M('order_goods')->field('activity_id,price_activity,goods_number,goods_price')->where('order_id = '.$order_id)->select();
	$goods_price = 0;
	foreach($list as $key => $value){
		$activity_title = M('store_activity')->where('id = '.$value['activity_id'])->getField('title');
		switch($activity_title){
			case '天天特价' :
				$goods_price += ($value['price_activity'] * $value['goods_number']);
				break;
			default :
				$goods_price += $value['goods_price'] * $value['goods_number'];
		}
	}
	*/
	return number_format($t1,2);
}

/*
*	获取订单数
*/
function getOrderCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order')->where(implode(' and ',$where))->count();
	return $count;
}

/*
*	获取已完成
*/
function getOrderYiWanChengCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`pay_status` = 1';
	$where[] = '`status` > 6';
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order')->where(implode(' and ',$where))->count();
	return $count;
}

function getOrderXiaDanCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`pay_status` = 0';
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order')->where(implode(' and ',$where))->count();
	return $count;
}

function getOrderPeiSongCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`pay_status` = 1';
	$where[] = '(`status` = 0 or `status` = 1 or `status` = 4 or `status` = 5 or `status` = 6)';
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order')->where(implode(' and ',$where))->count();
	return $count;
}

/*
*	获取退单数
*/
function getOrderTuiDanCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`pay_status` = 1';
	$where[] = '(`status` = 2 or `status` = 3)';
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'add_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'add_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order')->where(implode(' and ',$where))->count();
	return $count;
}

/*
*	获取骑手好评数
*/
function getPeiSongHaoPing(){
	$count = M('peisong_eval')->where('`lv` = 4 or `lv` = 5')->count();
	return $count;
}

/*
*	获取骑手差评数
*/
function getPeiSongChaPing(){
	$count = M('peisong_eval')->where('`lv` = 1 or `lv` = 2')->count();
	return $count;
}

/*
*	获取商户好评数
*/
function getShangHuHaoPing(){
	$count = M('store_eval')->where('`lv` = 4 or `lv` = 5')->count();
	return $count;
}

/*
*	获取商户好评数
*/
function getShangHuChaPing(){
	$count = M('store_eval')->where('`lv` = 1 or `lv` = 2')->count();
	return $count;
}

/*
*	获取商户好评数
*/
function getTouSu(){
	$count = M('tousu')->count();
	return $count;
}

/*
*	获取总收入
*/
function getOrderPriceCount(){
	$price = getOrderDaShangCount();
	$price += getOrderShangPinCount();
	return number_format($price,2);
}

/*
*	获取打赏费
*/
function getOrderDaShangCount(){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`status` > 6';
	if($_REQUEST['store_id'] != '') $where[] = 'store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'peisong_id = '.$_REQUEST['peisong_id'];
	$count = M('order as a')->where(implode(' and ',$where))->join('ythink_order_food as b on a.foreign_key = b.id')->getField('SUM(b.dashang_price)');
	return number_format($count,2);
}

/*
*	获取商品费
*/
function getOrderShangPinCount(){
	$where = array();
	$where[] = 'a.school_id = '.$_REQUEST['school_id'];
	$where[] = 'a.`status` > 6';
	$where[] = 'a.`pay_status` = 1';
	if($_REQUEST['store_id'] != '') $where[] = 'a.store_id = '.$_REQUEST['store_id'];
	if($_REQUEST['begin_time'] != '') $where[] = 'a.pay_time >= '.strtotime($_REQUEST['begin_time']);
	if($_REQUEST['end_time'] != '') $where[] = 'a.pay_time <= '.strtotime($_REQUEST['end_time']);
	if($_REQUEST['peisong_id'] != '') $where[] = 'a.peisong_id = '.$_REQUEST['peisong_id'];
	$price = M('order as a')->join('ythink_order_goods as b on a.id = b.order_id')->where(implode(' and ',$where).' and `activity_id` = 0')->getField('sum(goods_number * goods_price)');
	$list = M('order as a')->field('a.id')->join('ythink_order_goods as b on a.id = b.order_id')->where(implode(' and ',$where).' and `activity_id` > 0')->select();
	foreach($list as $key => $value){
		$price += getOrderZhenPirce($value['id']);
	}
	return number_format($price,2);
}

function getOrderShangPinCountToday($peisong_id){
	$where = array();
	$where[] = 'school_id = '.$_REQUEST['school_id'];
	$where[] = '`status` > 6';
	$where[] = '`pay_status` > 1';
	$where[] = 'peisong_id = '.$peisong_id;
	$where[] = 'add_time >= '.strtotime(date('Y-m-d').' 00:00:00');
	$where[] = 'add_time <= '.strtotime(date('Y-m-d').' 23:59:59');
	$price = M('order as a')->join('ythink_order_goods as b on a.id = b.order_id')->where(implode(' and ',$where).' and `activity_id` = 0')->getField('sum(goods_number * goods_price)');
	$list = M('order as a')->field('a.id')->join('ythink_order_goods as b on a.id = b.order_id')->where(implode(' and ',$where).' and `activity_id` > 0')->select();
	foreach($order as $key => $value){
		$price += getOrderZhenPirce($value['id']);
	}
	return number_format($price,2);
}

/*
*	新增用户
*/
function getOrderUserCount(){
	$count = M('users')->count();
	return $count;
}