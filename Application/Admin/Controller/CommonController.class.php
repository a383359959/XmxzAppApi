<?php
namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!session('?userInfo')) {
            $this->error('���½���ڲ���!', U('login/login'));
        }

        if($_SESSION['userInfo']['name']!=''){
            $caName= CONTROLLER_NAME.ACTION_NAME;

            $data=M('access')->alias('a')->join('left join jd_menu as b on a.menu_id=b.id')->where(array('a.role_id' => $_SESSION['userInfo']['role_id'],'b.pid'=>array('neq',0)))->select();
            foreach ($data as $key=>$value){
                $data_arr[]=$value['controller'].$value['action'];
            }
            if(!in_array($caName,$data_arr)){
                $this->error('��ǰ��ɫû��Ȩ��,����ϵ��������Ա��');
            }
        }
    }
}