<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-6-11
 * Time: 01:45
 */

namespace app\admin\controller;


use think\Controller;

class Index extends Controller
{
    function index(){

        $this->view->engine->layout(true);
       return $this->fetch();
    }

}