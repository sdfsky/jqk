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
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class DramaController extends HomeController {

    public function index() {
        $this->display();
    }

    public function detail() {
        $dramaid = I('get.dramaid');
        if (!($dramaid && is_numeric($dramaid))) {
            $this->error('ID错误！');
        }
        $map['id'] = $dramaid;
        $dramaModel = M('Drama');
        $drama = $dramaModel->where($map)->find();
        if (!$drama) {
            $this->error($dramaModel->getError());
        }
        $guesslikes = D('Drama')->guess_likes($dramaid);
        /* 更新浏览次数 */
        $dramaModel->where($map)->setInc('views');
        $this->assign("drama", $drama);
        $this->assign("guesslikes", $guesslikes);
        $this->display();
    }

    function search($word = '', $type = '0', $category = '0', $location = '0', $year = '0') {
        $map['status'] = array('neq', -1);
        if ($word) {
            $where['name'] = array('like', "$word%");
            $where['alias'] = array('like', "$word%");
            $where['_logic'] = 'OR';
            $map['_complex'] = $where;
        }
        $type && $map['type'] = array('eq', $type);
        $year && $map['age'] = array('eq', $year);
        $location && $map['region'] = array('eq', $location);
        $category && $map['_string'] = " `id` IN (SELECT dramaid FROM " . C('DB_PREFIX') . "open_category WHERE category_name='$category')";
        $rowcount = M('Drama')->where($map)->count();
        $page = new \Think\Page($rowcount, C('DEFAULT_LIST_ROWS'));
        $_page = $page->show();
        $categorys = D('Drama')->get_categorys();
        $years = D('Drama')->get_years();
        $locations = D('Drama')->get_locations();
        $dramalist = M('Drama')->where($map)->limit($page->firstRow, $page->listRows)->select();
        $this->assign('dramalist', $dramalist);
        $this->assign("word", $word);
        $this->assign("type", $type);
        $this->assign("category", $category);
        $this->assign("location", $location);
        $this->assign("year", $year);
        $this->assign("categorys", $categorys);
        $this->assign("locations", $locations);
        $this->assign("years", $years);
        $this->assign('_page', $_page);
        $this->display();
    }

    public function performer($performer_name) {
        if (!$performer_name) {
            $this->error("请求错误！");
        }
        $pmap['performer_name'] = $performer_name;
        $performer = M('Performer')->where($pmap)->find();
        $map['status'] = array('neq', -1);
        $map['_string'] = " `id` IN (SELECT dramaid FROM " . C('DB_PREFIX') . "drama_performer WHERE performer_name='$performer_name')";
        $rowcount = M('Drama')->where($map)->count();
        $page = new \Think\Page($rowcount, C('DEFAULT_LIST_ROWS'));
        $_page = $page->show();
        $dramalist = M('Drama')->where($map)->order("views DESC,update_time DESC")->limit($page->firstRow, $page->listRows)->select();
        $this->assign("performer", $performer);
        $this->assign("performer_name", $performer_name);
        $this->assign('dramalist', $dramalist);
        $this->display();
    }

    public function ajaxGetSummary($dramaid) {
        $map['id'] = array('eq', $dramaid);
        $drama = M('Drama')->where($map)->find();
        echo $drama['summary'];
    }

}
