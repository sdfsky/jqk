<?php

/**
 * Description of DramaModel
 *
 * @author simon
 */

namespace Home\Model;

use Think\Model;

class DramaModel extends Model {
    /* 获取分类 */

    public function get_categorys() {
        $category = M('open_category');
        $list = S('categorys');
        if (!$list) {
            $list = $category->where(true)->field('category_name,COUNT(dramaid) AS num')->group('category_name')->order('num desc')->limit(0, 20)->select();
            S('categorys', $list, 3600);
        }
        return $list;
    }

    public function get_years() {
        $drama = M('Drama');
        $list = S('years');
        if (!$list) {
            $list = $drama->where(true)->field('age,COUNT(id) AS num')->group('age')->order('num desc')->limit(0, 10)->select();
            S('years', $list, 3600);
        }
        return $list;
    }

    public function get_locations() {
        $drama = M('Drama');
        $list = S('locations');
        if (!$list) {
            $list = $drama->where(true)->field('region,COUNT(id) AS num')->group('region')->order('num desc')->limit(0, 10)->select();
            S('locations', $list, 3600);
        }
        return $list;
    }

    public function guess_likes($dramaid) {
        $dramalist = array();
        $drama = M('Drama')->where("`id`=$dramaid")->find();
        $performers = explode(" ", $drama['performers']);
        $map['performer_name'] = array('in', $performers);
        $map['dramaid'] = array('neq', $dramaid);
        $drama_performers = M('drama_performer')->distinct(true)->field('dramaid')->where($map)->limit(0, 50)->select();
        $dramaids = array();
        foreach ($drama_performers as $p) {
            $dramaids[] = $p['dramaid'];
        }
        if ($dramaids) {
            $map = array();
            $map['id'] = array('in', $dramaids);
            $dramalist = M('Drama')->where($map)->limit(0, 6)->select();
        }
        return $dramalist;
    }

}
