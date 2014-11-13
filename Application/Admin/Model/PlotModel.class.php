<?php

namespace Admin\Model;

use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PlotModel extends Model {
    /* 自动验证规则 */

    protected $_validate = array(
        array('plotindex', '/^[\d]+$/', '集数必须为整数', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '剧情内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('views', 0, self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_BOTH),
    );

}
