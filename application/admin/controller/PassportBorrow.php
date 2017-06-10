<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-6-10
 * Time: 15:15
 */

namespace app\admin\controller;


use think\Controller;

class PassportBorrow extends Controller
{
    function borrow(){

        return $this->fetch();
    }

    function Cacel(){


        return $this->fetch();
    }

}