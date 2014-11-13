<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

    //系统首页
    public function index($sorttype = 0) {
        $rowcount = M('Drama')->where("status<>-1")->count();
        $page = new \Think\Page($rowcount, C('DEFAULT_LIST_ROWS'));
        $_page = $page->show();
        $order = "update_time DESC";
        if ($sorttype) {
            $order = "views DESC,update_time DESC";
        }
        $dramalist = M('Drama')->where("status<>-1")->order($order)->limit($page->firstRow, $page->listRows)->select();
        
        $sliders = S('sliders');
        if(!$sliders){
            $sliders = M('Slider')->where(true)->order("`id` DESC")->limit(0,3)->select();
            S('sliders',$sliders,300);
        }
        
        
        $this->assign('_page', $_page);
        $this->assign('sorttype', $sorttype);
        $this->assign('sliders', $sliders);
        $this->assign('dramalist', $dramalist);
        $this->display();
    }

}
