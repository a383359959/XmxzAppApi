<?php

namespace Admin\Controller;

use Think\Controller;

class CountController extends Controller{

	public function count_order(){
		$xiaoliang = array();
		$number = date('d',strtotime(date('Y-m').'-1 + 1 month - 1 day'));
		for($i = 1;$i <= $number;$i++){
			$days[] = date('Y-m-'.$i);
		}
		foreach($days as $key => $value){
			$begin_time = strtotime($value.' 00:00:00');
			$end_time = strtotime($value.' 23:59:59');
			$xiaoliang[] = M('order')->where('school_id = '.$_REQUEST['school_id'].' and songda_time >= '.$begin_time.' and songda_time <= '.$end_time)->getField('count(*)');
		}
		$this->assign('days',json_encode($days));
		$this->assign('xiaoliang',json_encode($xiaoliang));
		$this->display();
	}
	
	
	
	
	public function tongji(){
		date_default_timezone_set('PRC');
		$year=$_POST['Y'];
		$month=$_POST['M'];
		$day=$_POST['D'];
		$s=$year."-".$month."-".$day;
		$shi=strtotime($s);//选中的日期对应的时间戳
		$list=M('order')->where('add_time > '.$shi)->select();

		$time = time() - $shi;

		$int = ceil($time / 86400); //几天
	
		$cou = array();
//订单个数和订单时间
		for($i = 1;$i < $int;$i++){
		
			$top=$shi+86400*$i;//开始时间
			$end=$shi+86400*(1+$i);//结束时间

			$cou[] = M('order')->where( "add_time>".$top ." and  add_time<".$end)->count();
			
		}
		$a=array();
		for($i = 1;$i < $int;$i++){
			$a[]=date("m-d",$shi+86400*$i);
		}
$b=array();
$b[1]=$a;
$b[2]=$cou;
		exit(json_encode($b));

	}

	public function count_goods(){
		$this->display();

	}
	//商品销量的统计
	public function goods_tongji(){
		set_time_limit(0);
		date_default_timezone_set('PRC');
		$year=$_POST['Y'];//年
		$month=$_POST['M'];
		$day=$_POST['D'];
		$name=$_POST['A'];//商家名
		$s=$year."-".$month."-".$day;

		$shi=strtotime($s);//选中的日期对应的时间戳
		$end=$shi+86400;
		//分两种情况 一。有商家名搜索 二。无商家名搜索
		if(!empty($name)){

			$a=M('store')->query("select * from ythink_store where store_name='$name' ");
			foreach ($a as $key => $value) {
				$s=$a[$key]['id'];
			}
			$list=M()->query("select g.id as g_id,sum(g.goods_number) as quantity,g.goods_name,o.add_time,o.id from ythink_order_goods as g left join ythink_order as o on o.id = g.order_id where o.add_time >$shi  and o.add_time < $end and o.store_id = $s group by order_id order by quantity desc ");
			foreach ($list as $key => $value) {
				$a[]=$list[$key]['goods_name'];
			}
			//
			foreach ($list as $key => $value) {
				$b[]=$list[$key]['quantity'];
			}
			$c=array();
			$c[0]=$a;
			$c[1]=$b;
			exit(json_encode($c));
			}else{

			$list=M()->query("select g.id as g_id,sum(g.goods_number) as quantity,g.goods_name,o.add_time,o.id from ythink_order_goods as g left join ythink_order as o on o.id = g.order_id where o.add_time > $shi and o.add_time < $end group by order_id order by quantity desc ");
			foreach ($list as $key => $value) {
				$a[]=$list[$key]['goods_name'];
			}
			foreach ($list as $key => $value) {
				$b[]=$list[$key]['quantity'];
			}
			$c=array();
			$c[0]=$a;
			$c[1]=$b;
			exit(json_encode($c));
			}
			
	 
	}

}
