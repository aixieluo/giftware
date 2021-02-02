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

    protected $field = [
        'notice',
    ];

    // 追加属性
    protected $append = [
        'type_text',
    ];

    public function getCreatetimeAttr()
    {
        return date('Y-m-d', $this->getData('createtime'));
    }


    public function getTypeTextAttr()
    {
        switch ($this->getData('type')) {
            case 1:
                return '新闻';
            case 2:
                return '帮助';
            default:
                return '错误类型，不会显示，请修改';
        }
    }






}
