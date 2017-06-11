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
        $data = Db::table('group')->paginate(10);
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    function add()
    {
        $this->view->engine->layout(true);
        if ($this->request->isPost()) {
            //获取数据，并将护照ids抽离，先保存团组，获得团组id，然后
            //将团组id与护照ids进行组合。拼装出团组-护照表格的插入数据
            $data = input();

            $pass_ids = $data['pass_ids'];
            unset($data['pass_ids']);
            $group = $data;
            //插入团组数据，获得团组id
            $sin = Db::table('group')->insert($group);
            $groupId = Db::name('group')->getLastInsID();

            //开始拼装人员信息

            for ($i = 0; $i < count($pass_ids); $i++) {
                $tem[$i]['group_id'] = $groupId;
                $tem[$i]['passport_id'] = $pass_ids[$i];
            }
            Db::table('group_passport')->insertAll($tem);
            $this->success('新建成功','lst');
        }
        return $this->fetch();
    }

    function edit()
    {
        $this->view->engine->layout(true);
        if ($this->request->isPost()) {//更新数据
            //获取数据，并将护照ids抽离，先更新团组，获得团组id，然后
            //将团组id与护照ids进行组合。拼装出团组-护照表格的插入数据
            $data = input();
            $new_pass_ids = $data['pass_ids'];
            unset($data['pass_ids']);
            $group = $data;


            //更新团组数据，获得团组idupdate
//            $sin = Db::table('group')->update($group);
//
//            //根据团组id取得原有的成员清单
            $passports = Db::table('group_passport')->where('group_id', $group['id'])->select();
            if($passports) {
                foreach ($passports as $v) {
                    $old_pass_ids[] = $v['passport_id'];
                }
            }else{
                $old_pass_ids=array();
            }


            $add = array_values(array_diff($new_pass_ids, $old_pass_ids));//需要新增的团组
            $del = array_values(array_diff($old_pass_ids, $new_pass_ids));//需要删除的团组

            $addlist = array();
            if ($add) {
                for ($i = 0; $i < count($add); $i++) {
                    $addlist[$i]['group_id'] = $group['id'];
                    $addlist[$i]['passport_id'] = $add[$i];
                }
                Db::table('group_passport')->insertAll($addlist);
            }
            if ($del) {
                for ($i = 0; $i < count($del); $i++) {
                    Db::table('group_passport')
                        ->where('group_id', $group['id'])
                        ->where('passport_id', $del[$i])
                        ->delete();
                }
            }
            $this->redirect('', ['id' => $group['id']]);
        }
        //根据团组id获取团组数据
        $id = input('id');

        $group = Db::table('group')->where('id', $id)->find();
        //根据团组id获取人员信息
        $passports = Db::table('group_passport')
            ->where('group_id', $id)
            ->alias('g')
            ->join('passport p', 'g.passport_id=p.id')
            ->select();
        //保存当前护照集合，以本表id为索引,以本团组的护照id+团组id的值作比较
        //再通过post数据里的护照id与团组id里的值作比较，判断当前团组里护照的增减情况
        //减少的删除，增加的新增
        foreach ($passports as $k => $v) {
            $this->current_pass_ids[] = $v['passport_id'];
        }


        $this->assign('group', $group);
        $this->assign('data', $passports);

        return $this->fetch();
    }

    function del()
    {
        $this->view->engine->layout(true);
        $id=input('id');
        Db::table('group')->delete($id);
        Db::table('group_passport')->where('group_id','eq',$id)->delete();
        $this->success('删除成功','lst');
    }
}