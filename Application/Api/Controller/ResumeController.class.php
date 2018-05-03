<?php

namespace Api\Controller;

use Think\Controller;

class ResumeController extends Controller{

	public function getField(){
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->getField($_REQUEST['field']);
		$result['data'] = $rs;
		die(json_encode($result));
	}

	public function setField(){
		$this->addResume();
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->getField('id');
		$data[$_REQUEST['field']] = $_REQUEST['data'];
		M('resume')->where('user_id = '.$_REQUEST['user_id'])->save($data);
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function addResume(){
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->getField('id');
		if(!$rs) M('resume')->add(array('user_id' => $_REQUEST['user_id']));
	}

	public function setUserInfo(){
		$data = array(
			'name' 			=>	$_REQUEST['name'],
			'sex' 			=> 	$_REQUEST['sex'],
			'birth_year'	=> 	$_REQUEST['birth_year'],
			'birth_month' 	=> 	$_REQUEST['birth_month'],
			'work_year' 	=> 	$_REQUEST['work_year'],
			'work_month' 	=> 	$_REQUEST['work_month'],
		);
		M('resume')->where('user_id = '.$_REQUEST['user_id'])->save($data);
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function getUserInfo(){
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->find();
		$rs['telephone'] = M('user')->where('id = '.$_REQUEST['user_id'])->getField('username');
		die(json_encode($rs));
	}

	public function getEducationDegree(){
		$list = M('resume_education_degree')->field('id as value,name as text')->order('sort asc')->select();
		die(json_encode($list));
	}

	public function setUserEducation(){
		$data = array(
			'user_id'				=> $_REQUEST['user_id'],
			'school_name' 			=>	$_REQUEST['school_name'],
			'degree' 				=> 	$_REQUEST['degree'],
			'admission_year'		=> 	$_REQUEST['admission_year'],
			'admission_month' 		=> 	$_REQUEST['admission_month'],
			'graduation_year' 		=> 	$_REQUEST['graduation_year'],
			'graduation_month' 		=> 	$_REQUEST['graduation_month'],
			'professional_name' 	=>	$_REQUEST['professional_name']
		);
		if($_REQUEST['type'] == 'add'){
			M('resume_education')->add($data);
		}else{
			M('resume_education')->where('id = '.$_REQUEST['id'])->save($data);
		}
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function getUserEducation(){
		$list = M('resume_education')->where('user_id = '.$_REQUEST['user_id'])->select();
		foreach($list as $key => $value){
			$list[$key]['degree_name'] = M('resume_education_degree')->where('id = '.$value['degree'])->getField('name');
		}
		$result['list'] = $list;
		die(json_encode($result));
	}

	public function delUserEducation(){
		M('resume_education')->where('id = '.$_REQUEST['id'])->delete();
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function getEducationInfo(){
		$rs = M('resume_education')->where('id = '.$_REQUEST['id'])->find();
		$rs['degree_name'] = M('resume_education_degree')->where('id = '.$rs['degree'])->getField('name');
		$rs['resume_education_degree'] = M('resume_education_degree')->field('id as value,name as text')->order('sort asc')->select();
		die(json_encode($rs));
	}

	public function setUserWorkExperience(){
		$data = array(
			'user_id'		=> $_REQUEST['user_id'],
			'company_name'	=>	$_REQUEST['company_name'],
			'start_year' 	=> 	$_REQUEST['start_year'],
			'start_month'	=> 	$_REQUEST['start_month'],
			'end_year' 		=> 	$_REQUEST['end_year'],
			'end_month' 	=> 	$_REQUEST['end_month'],
			'desc' 			=> 	$_REQUEST['desc'],
			'wages'			=> 	$_REQUEST['wages']
		);
		if($_REQUEST['type'] == 'add'){
			M('resume_work_experience')->add($data);
		}else{
			M('resume_work_experience')->where('id = '.$_REQUEST['id'])->save($data);
		}
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function getUserWorkExperience(){
		$list = M('resume_work_experience')->where('user_id = '.$_REQUEST['user_id'])->select();
		$result['list'] = $list;
		die(json_encode($result));
	}

	public function getUserWorkExperienceInfo(){
		$rs = M('resume_work_experience')->where('id = '.$_REQUEST['id'])->find();
		die(json_encode($rs));
	}

	public function delUserWorkExperience(){
		M('resume_work_experience')->where('id = '.$_REQUEST['id'])->delete();
		$result['status'] = 'success';
		die(json_encode($result));
	}

	public function checkResume(){
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->find();
		$resume_education = M('resume_education')->where('user_id = '.$_REQUEST['user_id'])->count();
		$resume_work_experience = M('resume_work_experience')->where('user_id = '.$_REQUEST['user_id'])->count();
		$result = array(
			'resume_name' 				=> !empty($rs['resume_name']) ? 'success' : 'error',
			'resume_info' 				=> !empty($rs['name']) && $rs['sex'] > 0 && $rs['birth_year'] > 0 && $rs['birth_month'] > 0 && $rs['work_year'] > 0 && $rs['work_month'] > 0 ? 'success' : 'error',
			'resume_education' 			=> $resume_education > 0 ? 'success' : 'error',
			'resume_work_experience' 	=> $resume_work_experience > 0 ? 'success' : 'error',
			'self_evaluation'			=> !empty($rs['self_evaluation']) ? 'success' : 'error',
			'name'						=> !empty($rs['name']) ? $rs['name'] : '请完善信息',
			'litpic'					=> !empty($rs['litpic']) ? $rs['litpic'] : 'error',
			'status'					=> $rs['status']
		);
		die(json_encode($result));
	}

	public function view(){
		$rs = M('resume')->where('user_id = '.$_REQUEST['user_id'])->find();
		$rs['telephone'] = M('user')->where('id = '.$_REQUEST['user_id'])->getField('username');
		$sex = '未填写';
		if($rs['sex'] == 1){
			$sex = '男';
		}else if($rs['sex'] == 2){
			$sex = '女';
		}
		$resume_education = M('resume_education')->where('user_id = '.$_REQUEST['user_id'])->select();
		$resume_work_experience = M('resume_work_experience')->where('user_id = '.$_REQUEST['user_id'])->select();

		$result['resume_info'] = '
			<li>
				<label>姓名：</label>
				<p>'.$rs['name'].'</p>
				<div class="clear"></div>
			</li>
			<li>
				<label>性别：</label>
				<p>'.$sex.'</p>
				<div class="clear"></div>
			</li>
			<li>
				<label>出生日期：</label>
				<p>'.$rs['birth_year'].' - '.$rs['birth_month'].'</p>
				<div class="clear"></div>
			</li>
			<li>
				<label>参加工作日期：</label>
				<p>'.$rs['work_year'].' - '.$rs['work_month'].'</p>
				<div class="clear"></div>
			</li>
			<li>
				<label>手机号码：</label>
				<p><a href="tel:'.$rs['telephone'].'">'.$rs['telephone'].'</a></p>
				<div class="clear"></div>
			</li>
		';

		foreach($resume_education as $key => $value){
			$resume_education_degree = M('resume_education_degree')->where('id = '.$value['degree'])->getField('name');
			$resume_education_html = '
				<div class="resume_view_boxs">
					<ul class="resume_info_li">
						<li>
							<label>学校名称：</label>
							<p>'.$value['school_name'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>专业名称：</label>
							<p>'.$value['professional_name'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>学历、学位：</label>
							<p>'.$resume_education_degree.'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>入学时间：</label>
							<p>'.$value['admission_year'].' - '.$value['admission_month'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>毕业时间：</label>
							<p>'.$value['graduation_year'].' - '.$value['graduation_month'].'</p>
							<div class="clear"></div>
						</li>
					</ul>
				</div>
			';
		}
		$result['resume_education'] = $resume_education_html;

		foreach($resume_work_experience as $key => $value){
			// $resume_education_degree = M('resume_education_degree')->where('id = '.$value['degree'])->getField('name');
			$resume_work_experience_html = '
				<div class="resume_view_boxs">
					<ul class="resume_info_li">
						<li>
							<label>公司名称：</label>
							<p>'.$value['company_name'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>开始时间：</label>
							<p>'.$value['start_year'].' - '.$value['start_month'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>结束时间：</label>
							<p>'.$value['end_year'].' - '.$value['end_month'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>工资待遇：</label>
							<p>￥ '.$value['wages'].'</p>
							<div class="clear"></div>
						</li>
						<li>
							<label>工作描述：</label>
							<p>'.$value['desc'].'</p>
							<div class="clear"></div>
						</li>
					</ul>
				</div>
			';
		}
		$result['resume_work_experience'] = $resume_work_experience_html;
		$result['self_evaluation'] = empty($rs['self_evaluation']) ? '暂无' : $rs['self_evaluation'];
		$result['resume_name'] = $rs['resume_name'];

		die(json_encode($result));
	}

	public function lists(){
		$limit = (($_REQUEST['page'] - 1) * 10).',10';
		$rs = M('user')->where('id = '.$_REQUEST['user_id'])->find();
		$list = M('user as a')->limit($limit)->field('b.resume_name,b.user_id')->join('`ythink_resume` as b on a.id = b.user_id')->where('b.status = 1 and a.city_id = '.$rs['city_id'])->select();
		$province_name = M('province')->where('pro_id = '.$rs['province_id'])->getField('province_name');
		$city_name = M('city')->where('city_id = '.$rs['city_id'])->getField('city_name');
		$area_name = M('area')->where('area_id = '.$rs['area_id'])->getField('area_name');
		foreach($list as $key => $value){
			$list[$key]['location'] = $province_name.' '.$city_name.' '.$area_name;
		}
		$result['list'] = $list;
		die(json_encode($result));
	}
	
}