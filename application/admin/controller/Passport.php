<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-6-10
 * Time: 01:46
 */

namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;

class Passport extends Controller
{
    function lst()
    {
        if($this->request->isGet()){
            $name='%'.input('name').'%';
            $num='%'.input('num').'%';

            $data=Db::table('passport')
                ->where('name','LIKE',$name)
                ->where('num','LIKE',$num)
                ->paginate(10);
        }else{
            $data=Db::name('passport')->paginate(10);
        }

        $page=$data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    function add()
    {
        if(\request()->isPost()){
            $data=input();
            Db::table('passport')->insert($data);

        }

        return view('');
    }

    function edit()
    {
        if($this->request->isPost()){
            $data=input();
            $data=Db::name('passport')->update($data);
            $this->success('修改成功', 'lst');
        }
        $id=input('id');
        $data=Db::name('passport')->where('id',$id)->find();
        $this->assign('passport',$data);
        return $this->fetch();
    }

    function del()
    {
        if($this->request->isGet()){
            $id=input('id');
            Db::name('passport')->delete($id);
           return $this->success('删除成功','lst');

        }

        return view();
    }
}