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
class DramaController extends AdminController {

    public function index() {
        $name = I('nickname');
        $map['status'] = array('gt', -1);
        $map['name'] = array('like', '%' . $name . '%');
        $list = $this->lists('Drama', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '剧情管理';
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $Drama = D('Drama');
            $Drama->create();
            $id = $Drama->add();
            if ($id) {
                $categorys = explode(" ", trim(I('post.categorys')));
                $categoryModel = M('open_category');
                foreach ($categorys as $category) {
                    $categoryModel->category_name = $category;
                    $categoryModel->dramaid = $id;
                    $categoryModel->add();
                }

                $performers = explode(" ", trim(I('post.performers')));
                $performerModel = M('drama_performer');
                foreach ($performers as $performer) {
                    $performerModel->performer_name = $performer;
                    $performerModel->dramaid = $id;
                    $performerModel->add();
                }

                $this->success('添加影视成功！', U('index'));
            } else {
                $this->error($Drama->getError());
            }
        } else {
            $this->meta_title = '添加影视';
            $this->display();
        }
    }

    public function edit($id = 0) {
        if (IS_POST) {
            $Drama = D('Drama');
            $Drama->create();
            $Drama->save();
            if ($Drama->getError()) {
                $this->error($Drama->getError());
            }
            $categorys = explode(" ", trim(I('post.categorys')));
            $categoryModel = M('open_category');
            $categoryModel->where("dramaid=$id")->delete();
            foreach ($categorys as $category) {
                $categoryModel->category_name = $category;
                $categoryModel->dramaid = $id;
                $categoryModel->add();
            }

            $performers = explode(" ", trim(I('post.performers')));
            $performerModel = M('drama_performer');
            $performerModel->where("dramaid=$id")->delete();
            foreach ($performers as $performer) {
                $performerModel->performer_name = $performer;
                $performerModel->dramaid = $id;
                $performerModel->add();
            }

            $this->success('编辑影视成功！', U('index'));
        } else {
            $info = M('Drama')->find($id);
            $this->meta_title = '编辑影视';
            $this->assign("info", $info);
            $this->display();
        }
    }

    public function recyle() {
        $name = I('name');
        $map['status'] = array('eq', -1);
        $map['name'] = array('like', '%' . $name . '%');
        $list = $this->lists(M('Drama'), $map, 'update_time desc');
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
        $Model = 'Drama';
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
        $res = D('Drama')->where($map)->delete();
        if ($res !== false) {
            $this->success('清空回收站成功！');
        } else {
            $this->error('清空回收站失败！');
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
                $this->forbid('Drama', $map);
                break;
            case 'resumedrama':
                $this->resume('Drama', $map);
                break;
            case 'deletedrama':
                $this->delete('Drama', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }

}
