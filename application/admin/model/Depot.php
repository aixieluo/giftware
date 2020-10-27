<?php

namespace app\admin\model;

use think\Model;


class Depot extends Model
{





    // 表名
    protected $name = 'depot';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'gids'
    ];

    public function getGidsAttr()
    {
        return $this->gifts()->column('gift_id');
    }

    public function gifts()
    {
        return $this->belongsToMany(Gift::class, 'gift_depot');
    }
}
