<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

class PlotController extends HomeController {

    public function detail($dramaid,$plotindex = 1) {
        echo $plotindex;
        if (!($plotindex && is_numeric($plotindex))) {
            $this->error('ID错误！');
        }
        $map['plotindex'] = $plotindex;
        $map['dramaid'] = $dramaid;
        $plotModel = M('Plot');
        $plot = $plotModel->where($map)->find();
        /* 更新浏览次数 */
        $plotModel->where($map)->setInc('views');
        $dramaModel = M('Drama');
        $dramaModel->where(array('id' => $dramaid))->setInc("views");
        $drama = $dramaModel->where(array('id' => $dramaid))->find();
        if (!$drama) {
            $this->error("影视不存在");
        }
        $guesslikes = D('Drama')->guess_likes($dramaid);
        $this->assign("plot", $plot);
        $this->assign("drama", $drama);
        $this->assign("guesslikes", $guesslikes);
        $this->assign("plotindex", $plotindex);

        $this->display();
    }

}
