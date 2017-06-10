<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-6-10
 * Time: 15:15
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\View;
class PassportBorrow extends Controller
{
    function borrow()
    {

        $this->view->engine->layout(true);
        return $this->fetch();
    }

    function Cancel()
    {


        return $this->fetch();
    }

    function selectlst()//选择护照的界面
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

        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->view->engine->layout_on=false;
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    function add()
    {
        if($this->request->isPost()){
            $data=[];
                $tem=input();
                for($i=0;$i<count($tem['pass_ids']);$i++){
                    $data['passport_id'][]=$tem['pass_ids'][$i];
                    $data['borrow_name'][]=$tem['borrow_name'];
                    $data['why'][]=$tem['why'];
                    $data['group_id'][]=$tem['group_id'];
                    $data['beizhu'][]=$tem['beizhu'];
                }
                Db::table('passport_in_out')->insert($data);
                $this->success('借出成功');
        }
        else {
            $this->error('非法请求', 'passport/lst');
        }
    }


}