<?php
namespace Admin\Controller;

/**
 * 功能：权限管理
 * 作者：汪海飞
 * 时间：7-12
 */

class RoleController extends \Think\Controller
{
    /**
     * 角色展示
     */
    public function roleList()
    {
        $datas=M('rolelist')->select();
        $this->assign('datas',$datas);
        $this->display();
    }

    /**
     * 角色添加
     */
    public function roleAdd()
    {
       
        if(IS_POST){
            $data=I('post.');
            $res=M('rolelist')->add($data);
            if($res){
                $this->success('添加角色成功',U('roleList'));
            }else{
                $this->error('添加角色失败',U('roleAdd'));
            }
        }else{
            $this->display();
        }
    }

    /**
     * 角色修改
     */
    public function roleEdit()
    {
        $role=M('role');
        $id=I('get.id');
        if(IS_POST){
            $data=I('post.');
            $data['id']=$id;
            $res=$role->save($data);
            if($res){
                $this->success('角色修改成功',U('roleList'));
            }else{
                $this->error('角色修改失败',U('roleEdit',array(id=>$id)));
            }
        }else{
            $data=$role->where("id='$id'")->find();
            $this->assign('data',$data);
            $this->display();
        }
    }

    /**
     * 角色删除
     */
    public function roleDelete()
    {
        $role=M('role');
        $id=I('get.id');
        $res=$role->where("id='$id'")->delete();
        if($res){
            $this->success('角色删除成功',U('roleList'));
        }else{
            $this->error('角色删除失败',U('roleEdit',array(id=>$id)));
        }
    }

    /**
     * 权限列表
     */
    public function accessList()
    {
        $id=I('get.id');
        $role=M('role');
        $data=$role->where("id='$id'")->find();
        $this->assign('data',$data);

        $menu=M('menu');
        $data=$menu->select();
        $dataTree=list_to_tree($data);
        $this->assign('dataTree',$dataTree);

        $access_datas = M('access')->field('menu_id')->where('role_id=' . $id)->select();
        $access_datas = array_column($access_datas, 'menu_id');
        $this->assign('access_datas',$access_datas);
        $this->display();
    }

    /**
     * 权限修改
     */
    public function accessEdit()
    {
        if (IS_POST) {
            $datas = I('post.');
//            var_dump($datas);die;
            foreach ($datas['menu'] as $key => $value) {
                $data[$key]['role_id'] = $datas['role_id'];
                $data[$key]['menu_id'] = $value;
            }
            M('access')->where('role_id='.$datas['role_id'])->delete();
            $res = M('access')->addAll($data);
            if ($res) {
                $this->success('操作成功!', U('roleList'));exit;
            } else {
                $this->error('操作失败!');
            }
        }
    }
}