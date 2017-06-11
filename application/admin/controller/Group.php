<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-6-10
 * Time: 01:46
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class Group extends Controller
{
    function lst()
    {
        $this->view->engine->layout(true);
        $data=Db::table('group')->paginate(10);
        $page=$data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    function add()
    {
        $this->view->engine->layout(true);
        return $this->fetch();
    }

    function edit()
    {
        return $this->fetch();
    }

    function del()
    {
        return $this->fetch();
    }
}