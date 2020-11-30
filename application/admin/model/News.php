<?php

namespace app\admin\model;

use think\Model;


class News extends Model
{





    // 表名
    protected $name = 'news';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function getCreatetimeAttr()
    {
        return date('Y-m-d', $this->getData('createtime'));
    }









}