<?php

namespace Admin\Controller;

use Think\Controller;

class IndexController extends Controller{
	
    public function index(){
		
		if(session('admin_user_id') == ''){
			header('location:'.U('login'));
			exit;
		}
		$this->display();
    }
	// 导入方法，不要随便进入
	public function import(){
		echo '<meta charset="utf-8" />';
		$path = dirname(THINK_PATH).'/All_Data_Readable.csv';
		$content = fopen($path,'r');
		// $content = iconv('gbk','utf-8',$content);
		$arr = $this->input_csv($content);
		// print_r($arr);
		exit;
		for($i = 1;$i < count($arr);$i++){
			// var_dump(iconv('gbk','utf-8',$arr[$i][11]) == '男');
			$data = array();
			$data['name'] = $arr[$i][10];
			$data['sex'] = $arr[$i][11] == '男' ? 0 : 1;
			$data['birth_year'] = $arr[$i][12];
			$data['birth_month'] = $arr[$i][13];
			$data['birth_day'] = $arr[$i][14];
			$data['marriage'] = $arr[$i][15] == '未婚' ? 0 : 1;
			switch($arr[$i][16]){
				case '初中及以下' :
					$data['education'] = 0;
					break;
				case '高中':
					$data['education'] = 1;
					break;
				case '大专':
					$data['education'] = 2;
					break;
				case '本科':
					$data['education'] = 3;
					break;
				case '硕士及以上':
					$data['education'] = 4;
					break;
			}
			$data['mobile'] = $arr[$i][17];
			$data['email'] = $arr[$i][18];
			$data['job'] = $arr[$i][19];
			$data['income'] = $arr[$i][20];
			$data['investment'] = $arr[$i][21];
			
			if(!empty($arr[$i][22])) $data['way'] = 0;
			if(!empty($arr[$i][23])) $data['way'] = 1;
			if(!empty($arr[$i][24])) $data['way'] = 2;
			if(!empty($arr[$i][25])) $data['way'] = 3;
			if(!empty($arr[$i][26])) $data['way'] = 4;
			
			$data['experience'] = $arr[$i][27] == '是' ? 0 : 1;
			$data['start'] = $arr[$i][28] == '尽快，决定了，马上启动' ? 0 : 1;
			$data['investor'] = $arr[$i][29] == '独资' ? 0 : 1;
			$data['s1'] = $arr[$i][30];
			$data['s2'] = $arr[$i][31];
			$data['s3'] = $arr[$i][32];
			$data['s4'] = $arr[$i][33];
			
			// $data['s5'] = $arr[$i][33];
			
			// 这里差省市区
			$data['s5'] = $this->getInfoLike($arr[$i][34],'provinces','province_name','pro_id');
			$data['s6'] = $this->getInfoLike($arr[$i][35],'cities','city_name','city_id');
			$data['s7'] = $this->getInfoLike($arr[$i][36],'area','area_name','dis_id');
			
			$data['s8'] = $this->getInfoLike($arr[$i][37],'provinces','province_name','pro_id');
			$data['s9'] = $this->getInfoLike($arr[$i][38],'cities','city_name','city_id');
			$data['s10'] = $this->getInfoLike($arr[$i][39],'area','area_name','dis_id');
			
			$data['s11'] = $arr[$i][40];
			$data['s12'] = $arr[$i][41];
			$data['s13'] = $arr[$i][42];
			$data['s14'] = $arr[$i][43];
			
			// print_r($data);
			// exit;
			$param['ip'] = $arr[$i][1];
			$param['begin_time'] = strtotime($arr[$i][6]);
			$param['end_time'] = strtotime($arr[$i][7]);
			$param['data'] = serialize($data);
			M('list')->add($param);
			
		}
	}
	
	function getInfoLike($value,$table,$name,$return){
		$find = M($table)->where($name.' = "'.$value.'"')->find();
		if($find[$return]){
			return $find[$return];
		}else{
			return 0;
		}
	}
	
	function input_csv($handle) { 
		$out = array();
		$n = 0;
		while($data = fgetcsv($handle,10000)){ 
			$num = count($data); 
			for ($i = 0;$i < $num; $i++){
				$out[$n][$i] = iconv('gbk','utf-8',$data[$i]);
			}
			$n++;
		}
		return $out; 
	}
	
	public function changePassword(){
		$find = M('admin')->where('id = '.session('admin_user_id'))->find();
		$this->assign('find',$find);
		$this->display();
	}
	
	public function delete(){
		M('list')->where('id = '.$_REQUEST['id'])->delete();
		$this->success('删除成功',U('index'));
	}
	
	public function password_save(){
		if(empty($_REQUEST['password'])){
			$this->error('修改失败',U('changePassword'));
		}else{
			if($_REQUEST['password'] != $_REQUEST['confirm_password']){
				$this->error('两次密码输入不一致',U('changePassword'));
			}else{
				M('admin')->where('id = '.session('admin_user_id'))->save(array('password' => md5($_REQUEST['password'])));
				$this->success('修改成功',U('changePassword'));
			}
		}
	}
	
	public function getData($pro_id,$city_id,$dis_id){
		$provinces = M('provinces')->where('pro_id = '.$pro_id)->find();
		$cities = M('cities')->where('city_id = '.$city_id)->find();
		$area = M('area')->where('dis_id = '.$dis_id)->find();
		return $provinces['province_name'].' '.$cities['city_name'].' '.$area['area_name'];
	}
	
	public function email(){
		$find = M('email')->where('id = 1')->find();
		$find = unserialize($find['data']);
		$this->assign('find',$find);
		$this->display();
	}
	
	public function email_save(){
		$config = array(
			'smtp' => $_REQUEST['smtp'],
			'send_email' => $_REQUEST['send_email'],
			'send_email_password' => $_REQUEST['send_email_password'],
			'email_address' => $_REQUEST['email_address'],
			'url' => $_REQUEST['url'],
		);
		M('email')->where('id = 1')->save(array('data' => serialize($config)));
		$this->success('保存成功',U('email'));
	}
	
	public function login(){
		$this->display();
	}
	
	public function logout(){
		session('admin_user_id','');
		$this->success('退出成功',U('login'));
	}
	
	public function checkLogin(){
		$user = M('admin')->where('username = "'.$_REQUEST['username'].'" and password = "'.md5($_REQUEST['password']).'"')->find();
		if($user){
			session('admin_user_id',$user['id']);
			$this->success('登录成功',U('index'));
		}else{
			$this->error('帐号或者密码错误',U('login'));
		}
	}
	
}