<?php
namespace Admin\Model;
use Think\Model;

class AdvertModel extends Model{
	
	public function getAll(){
		$list=$this->select();
		$status = array('正常','过期');
    foreach($list as $key=>$val){
      $list[$key]['status'] = $status[ $val['status'] ]; 
    }
		return $list;
	}
}
