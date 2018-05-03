<?php

namespace Api\Controller;

use Think\Controller;

class UserController extends Controller{

	public function user_info(){
		$user = M('user')->where('id = '.$_REQUEST['user_id'])->find();
		$province_name = M('province')->where('pro_id = '.$user['province_id'])->getField('province_name');
		$city_name = M('city')->where('city_id = '.$user['city_id'])->getField('city_name');
		$area_name = M('area')->where('area_id = '.$user['area_id'])->getField('area_name');
		$mechanism_name = M('mechanism')->where('id = '.$user['mechanism_id'])->getField('name');
		$user['jigou'] = $province_name.'&nbsp;'.$city_name.'&nbsp;'.$area_name.'&nbsp;'.$mechanism_name;
		$user['role'] = $user['role'];
		die(json_encode($user));
	}

	public function reg(){
		$user = M('user')->field('id')->where('username = "'.$_REQUEST['username'].'"')->find();
		if($user) die(json_encode(array('status' => 'error','msg' => '用户名已存在！')));
		$data = array(
			'username'		=> $_REQUEST['username'],
			'password' 		=> md5($_REQUEST['password']),
			'reg_ip'		=> $_SERVER['REMOTE_ADDR'],
			'name'			=> substr_replace($_REQUEST['username'],'****',3,4),
			'add_time'		=> time(),
			'role'			=> $_REQUEST['role'],
			'province_id'	=> $_REQUEST['province_id'],
			'city_id'		=> $_REQUEST['city_id'],
			'area_id'		=> $_REQUEST['area_id'],
			'mechanism_id'	=> $_REQUEST['mechanism_id'],
		);
		M('user')->add($data);
		die(json_encode(array('status' => 'success')));
	}
	
	public function login(){
		$user = M('user')->where('username = "'.$_REQUEST['username'].'" and password = "'.md5($_REQUEST['password']).'"')->find();
		if(!$user) die(json_encode(array('status' => 'error','msg' => '用户名或者密码错误！')));
		$result = array(
			'status' => 'success',
			'id' => $user['id'],
			'username' => $user['username'],
			'role' => $user['role'],
		);
		die(json_encode($result));
	}
	
	public function uploadImg(){
		$file = $_FILES['file'];
		$name = $file['name'];
		$type = strtolower(substr($name,strrpos($name,'.')+1));
		$allow_type = array('jpg','jpeg','gif','png');
		if(!in_array($type, $allow_type)) exit(json_encode(array('status' => 'error','msg' => '文件类型不正确')));
		$upload_path = 'Upload/';
		$up_name = time().'_'.uniqid().'.'.$type;
		if(move_uploaded_file($file['tmp_name'],$upload_path.$up_name)){
			exit(json_encode(array('status' => 'success','path' => $upload_path.$up_name)));
		}else{
			exit(json_encode(array('status' => 'error','msg' => '上传图片错误，请联系网站管理员')));
		}
	}
	
	public function changeHeadimg(){
		$data['headimg'] = $_REQUEST['headimg'];
		M('user')->where('id = '.$_REQUEST['user_id'])->save($data);
		die(json_encode(array('status' => 'success')));
	}
	
	public function change_name(){
		$data['name'] = $_REQUEST['name'];
		M('user')->where('id = '.$_REQUEST['user_id'])->save($data);
		die(json_encode(array('status' => 'success')));
	}
	
	public function change_sex(){
		$data['sex'] = $_REQUEST['sex'];
		M('user')->where('id = '.$_REQUEST['user_id'])->save($data);
		die(json_encode(array('status' => 'success')));
	}

	public function getProvince(){
		$province = M('province')->select();
		$html = '<option value="0">请选择</option>';
		foreach($province as $key => $value){
			$html .= '<option value="'.$value['pro_id'].'">'.$value['province_name'].'</option>';
		}
		$result['html'] = $html;
		die(json_encode($result));
	}

	public function getCity(){
		$city = M('city')->where('pro_id = '.$_REQUEST['pro_id'])->select();
		$html = '<option value="0">请选择</option>';
		foreach($city as $key => $value){
			$html .= '<option value="'.$value['city_id'].'">'.$value['city_name'].'</option>';
		}
		$result['html'] = $html;
		die(json_encode($result));
	}

	public function getArea(){
		$area = M('area')->where('city_id = '.$_REQUEST['city_id'])->select();
		$html = '<option value="0">请选择</option>';
		foreach($area as $key => $value){
			$html .= '<option value="'.$value['area_id'].'">'.$value['area_name'].'</option>';
		}
		$result['html'] = $html;
		die(json_encode($result));
	}

	public function getMechanism(){
		$mechanism = M('mechanism')->where('area_id = '.$_REQUEST['area_id'])->select();
		$html = '<option value="0">请选择</option>';
		foreach($mechanism as $key => $value){
			$html .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$result['html'] = $html;
		die(json_encode($result));
	}

	public function accountAuditList(){
		$rs = M('user')->where('id = '.$_REQUEST['user_id'])->find();
		if($rs['role'] == 1){
			$list = M('user')->field('username,id')->where('role = 0 and `status` = 0 and mechanism_id = '.$rs['mechanism_id'])->select();
		}else if($rs['role'] == 2){
			$list = M('user')->field('username,id')->where('role = 1 and `status` = 0 and mechanism_id = '.$rs['mechanism_id'])->select();
		}
		$result['list'] = $list;
		die(json_encode($result));
	}

	public function accountAuditChange(){
		M('user')->where('id = '.$_REQUEST['user_id'])->save(array('status' => $_REQUEST['status']));
		$result['status'] = 'success';
		die(json_encode($result));
	}
	
}