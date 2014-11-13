<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;

use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class DramaModel extends Model {
    /* 自动验证规则 */

    protected $_validate = array(
        array('name', 'require', '影视名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('age', '/^[\d]+$/', '年代填写错误', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('region', 'require', '地区不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('plots', '/^[\d]+$/', '电视集数填写错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('latest_plot_index', 0, self::MODEL_INSERT),
        array('latest_plot_content', '', self::MODEL_INSERT),
        array('performers', 'addPerformers', self::MODEL_BOTH, 'callback'),
        array('latest_plot_content', 'getPlotContent', self::MODEL_INSERT, 'callback'),
        array('create_time', 'getCreateTime', self::MODEL_BOTH, 'callback'),
        array('update_time', 'getUpdateTime', self::MODEL_BOTH, 'callback'),
    );

    /* 添加演员 */

    public function addPerformers() {
        $performers = explode(" ", trim(I('post.performers')));
        foreach ($performers as $performer) {
            $performerModel = M('performer');
            $map = array('performer_name' => $performer);
            $res = $performerModel->where($map)->getField('id');
            if (!$res) {
                $performerModel->performer_name = $performer;
                $performerModel->add();
            }
        }
        return trim(I('post.performers'));
    }

    function getPlotContent() {
        return I('post.summary');
    }

    public function getCreateTime() {
        $create_time = I('post.create_time');
        return $create_time ? strtotime($create_time) : NOW_TIME;
    }

    public function getUpdateTime() {
        $update_time = I('post.update_time');
        return $update_time ? strtotime($update_time) : NOW_TIME;
    }

}
