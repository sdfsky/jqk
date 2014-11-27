<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <sky_php@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台内容控制器
 * @author huajie <sky_php@qq.com>
 */
class PlotController extends AdminController {

    public function index() {
        $name = I('nickname');
        $dramaid = I('get.dramaid');
        $map['status'] = array('gt', -1);
        $map['daram_name'] = array('like', '%' . $name . '%');
        $dramaid && $map['dramaid'] = $dramaid;
        $list = $this->lists('Plot', $map);
        int_to_string($list);
        $daram = M('Drama')->where("id='$dramaid'")->find();
        $this->assign('drama', $daram);
        $this->assign('dramaid', $dramaid);
        $this->assign('_list', $list);
        $this->meta_title = '剧情管理';
        $this->display();
    }

    public function add() {
        $dramaid = I('dramaid');
        if (IS_POST) {
            $Plot = D('Plot');
            if ($Plot->create() && $Plot->add()) {
                $dramaModel = M('Drama');
                $dramaModel->update_time = NOW_TIME;
                $dramaModel->latest_plot_index = I('post.plotindex');
                $dramaModel->latest_plot_content = I('post.content');
                $dramaModel->where("id=$dramaid")->save();
                $this->success('添加剧情成功！', U('index?dramaid=' . $dramaid));
            } else {
                $this->error($Plot->getError());
            }
        } else {
            $daram = M('Drama')->where("id='$dramaid'")->find();
            $this->assign('drama', $daram);

            $this->meta_title = '添加影视';
            $this->display();
        }
    }

    public function edit($id = 0) {
        $info = M('Plot')->find($id);
        if (IS_POST) {
            $Plot = D('Plot');
            if ($Plot->create() && $Plot->save()) {
                $this->success('编辑剧情成功！', U('index?dramaid=' . $info['dramaid']));
            } else {
                $this->error($Plot->getError());
            }
        } else {
            $this->meta_title = '编辑剧情';
            $this->assign("info", $info);
            $this->display();
        }
    }

    public function recyle() {
        $name = I('name');
        $map['status'] = array('eq', -1);
        $map['name'] = array('like', '%' . $name . '%');
        $list = $this->lists(M('Plot'), $map, 'create_time desc');
        $this->assign('_list', $list);
        $this->meta_title = '回收站';
        $this->display();
    }

    /**
     * 还原被删除的数据
     * @author huajie <banhuajie@163.com>
     */
    public function permit() {
        /* 参数过滤 */
        $ids = I('param.ids');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }

        /* 拼接参数并修改状态 */
        $Model = 'Plot';
        $map = array();
        if (is_array($ids)) {
            $map['id'] = array('in', $ids);
        } elseif (is_numeric($ids)) {
            $map['id'] = $ids;
        }
        $this->restore($Model, $map);
    }

    public function clear() {
        $ids = I('post.id');
        $map['id'] = array('in', $ids);
        $res = D('Plot')->where($map)->delete();
        if ($res !== false) {
            $this->success('剧情删除成功！');
        } else {
            $this->error('剧情删除失败！');
        }
    }

    public function changeStatus($method = null) {
        $id = array_unique((array) I('id', 0));
        $id = is_array($id) ? implode(',', $id) : $id;
        if (empty($id)) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] = array('in', $id);
        switch (strtolower($method)) {
            case 'forbiddrama':
                $this->forbid('Plot', $map);
                break;
            case 'resumedrama':
                $this->resume('Plot', $map);
                break;
            case 'deletedrama':
                $this->delete('Plot', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }

}
